<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentLog;
use App\Models\Attendance;
use App\Models\AttendanceCheckoutCorrection;
use App\Models\User;
use App\Notifications\CheckoutCorrectionRequested;
use App\Notifications\CheckoutCorrectionReviewed;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceCheckoutCorrectionService
{
    public function request(User $user, Assignment $assignment, string $date, string $requestedTime, string $reason): AttendanceCheckoutCorrection
    {
        $employee = $user->employee;
        if (!$employee || !$assignment->daily_attendance_enabled || !$assignment->employees()->where('employees.id', $employee->id)->exists()) abort(403);

        $attendanceDate = Carbon::parse($date)->startOfDay();
        if (!$attendanceDate->lt(today())) throw ValidationException::withMessages(['date'=>['Koreksi hanya untuk attendance hari sebelumnya yang lupa Check Out.']]);

        $attendance = Attendance::query()->where('employee_id',$employee->id)->where('assignment_id',$assignment->id)->where('attendance_type','ASSIGNMENT')->whereDate('attendance_date',$attendanceDate)->first();
        if (!$attendance || !$attendance->is_checked_in) throw ValidationException::withMessages(['attendance'=>['Koreksi Check Out tidak tersedia karena tidak ada Check In pada hari tersebut. Lupa Check In tidak dapat dikoreksi.']]);
        if ($attendance->is_checked_out) throw ValidationException::withMessages(['attendance'=>['Attendance tersebut sudah memiliki Check Out.']]);
        if (AttendanceCheckoutCorrection::where('attendance_id',$attendance->id)->where('status','Pending')->exists()) throw ValidationException::withMessages(['attendance'=>['Pengajuan koreksi untuk attendance ini masih menunggu review Company.']]);

        $checkInRaw = $attendance->getRawOriginal('check_in_time') ?: optional($attendance->check_in_time)->format('H:i:s');
        $checkIn = Carbon::parse($attendanceDate->toDateString().' '.$checkInRaw);
        $requested = Carbon::parse($attendanceDate->toDateString().' '.$requestedTime);
        $dayLimit = Carbon::parse($attendanceDate->toDateString().' 23:00:00');
        if (!$requested->gt($checkIn) || $requested->gt($dayLimit)) throw ValidationException::withMessages(['requested_check_out_time'=>['Jam Check Out harus setelah Check In dan maksimal 23:00 pada hari yang sama.']]);

        $correction = AttendanceCheckoutCorrection::create([
            'company_id'=>$employee->company_id,'assignment_id'=>$assignment->id,'attendance_id'=>$attendance->id,'employee_id'=>$employee->id,
            'requested_check_out_time'=>$requested->format('H:i:s'),'reason'=>trim($reason),'status'=>'Pending',
        ]);

        AssignmentLog::create(['assignment_id'=>$assignment->id,'employee_id'=>$employee->id,'user_id'=>$user->id,'action'=>'CHECKOUT_CORRECTION_REQUESTED','description'=>'Employee requested a missed Check Out correction.','properties'=>['attendance_date'=>$attendanceDate->toDateString(),'requested_check_out_time'=>$requested->format('H:i:s'),'reason'=>trim($reason)]]);
        User::query()->companyAdminsOf($employee->company_id)->get()->each(fn(User $admin) => $admin->notify(new CheckoutCorrectionRequested($correction)));
        return $correction->fresh(['attendance','assignment','employee']);
    }

    public function approve(User $reviewer, Assignment $assignment, AttendanceCheckoutCorrection $correction, ?string $notes = null): AttendanceCheckoutCorrection
    {
        $this->authorizeCompany($reviewer,$assignment,$correction);
        return DB::transaction(function () use ($reviewer,$assignment,$correction,$notes) {
            $correction = AttendanceCheckoutCorrection::query()->lockForUpdate()->findOrFail($correction->id);
            if (!$correction->isPending()) throw ValidationException::withMessages(['correction'=>['Pengajuan ini sudah direview.']]);
            $attendance = Attendance::query()->lockForUpdate()->findOrFail($correction->attendance_id);
            if ($attendance->is_checked_out) throw ValidationException::withMessages(['attendance'=>['Attendance sudah memiliki Check Out.']]);

            $date = $attendance->attendance_date->toDateString();
            $checkInRaw = $attendance->getRawOriginal('check_in_time') ?: optional($attendance->check_in_time)->format('H:i:s');
            $checkIn = Carbon::parse($date.' '.$checkInRaw);
            $checkOut = Carbon::parse($date.' '.substr((string)$correction->requested_check_out_time,0,8));
            $expectedEnd = Carbon::parse($date.' '.$assignment->end_datetime->format('H:i:s'));
            $work = max(0,(int)round(abs($checkIn->diffInMinutes($checkOut))));
            $early = $checkOut->lt($expectedEnd) ? max(0,(int)round(abs($checkOut->diffInMinutes($expectedEnd)))) : 0;
            $overtime = $checkOut->gt($expectedEnd) ? max(0,(int)round(abs($expectedEnd->diffInMinutes($checkOut)))) : 0;

            $attendance->update(['check_out_time'=>$checkOut->format('H:i:s'),'is_checked_out'=>true,'work_minutes'=>$work,'early_leave_minutes'=>$early,'overtime_minutes'=>$overtime]);
            $correction->update(['status'=>'Approved','reviewed_by'=>$reviewer->id,'review_notes'=>$notes,'reviewed_at'=>now()]);
            AssignmentLog::create(['assignment_id'=>$assignment->id,'employee_id'=>$correction->employee_id,'user_id'=>$reviewer->id,'action'=>'CHECKOUT_CORRECTION_APPROVED','description'=>'Company approved missed Check Out correction.','properties'=>['attendance_date'=>$date,'approved_check_out_time'=>$checkOut->format('H:i:s'),'work_minutes'=>$work,'early_leave_minutes'=>$early,'overtime_minutes'=>$overtime]]);
            $fresh=$correction->fresh(['assignment','employee.user']); $fresh->employee?->user?->notify(new CheckoutCorrectionReviewed($fresh));
            return $fresh;
        });
    }

    public function reject(User $reviewer, Assignment $assignment, AttendanceCheckoutCorrection $correction, ?string $notes = null): AttendanceCheckoutCorrection
    {
        $this->authorizeCompany($reviewer,$assignment,$correction);
        if (!$correction->isPending()) throw ValidationException::withMessages(['correction'=>['Pengajuan ini sudah direview.']]);
        $correction->update(['status'=>'Rejected','reviewed_by'=>$reviewer->id,'review_notes'=>$notes,'reviewed_at'=>now()]);
        AssignmentLog::create(['assignment_id'=>$assignment->id,'employee_id'=>$correction->employee_id,'user_id'=>$reviewer->id,'action'=>'CHECKOUT_CORRECTION_REJECTED','description'=>'Company rejected missed Check Out correction.','properties'=>['review_notes'=>$notes]]);
        $fresh=$correction->fresh(['assignment','employee.user']); $fresh->employee?->user?->notify(new CheckoutCorrectionReviewed($fresh));
        return $fresh;
    }

    private function authorizeCompany(User $reviewer, Assignment $assignment, AttendanceCheckoutCorrection $correction): void
    {
        abort_unless($reviewer->company_id && $assignment->company_id === $reviewer->company_id && $correction->company_id === $reviewer->company_id && $correction->assignment_id === $assignment->id,403);
    }
}
