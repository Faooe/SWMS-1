<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    public function profile(User $user): User
    {
        return $user->load([
            'role',
            'company',
            'employee.currentEmployment.department',
            'employee.currentEmployment.position',
            'employee.currentEmployment.team',
            'employee.currentEmployment.office',
            'employee.currentEmployment.shift',
        ]);
    }

    public function updateProfile(User $user, array $data): User
    {
        $passwordChanged = ! empty($data['password']);

        if ($passwordChanged && empty($data['current_password'])) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama wajib diisi untuk mengubah password.',
            ]);
        }

        if (! empty($data['current_password'])) {
            $this->assertCurrentPassword($user, $data['current_password']);
        }

        $updateData = [
            'username' => $data['username'],
            'email' => $data['email'],
        ];

        if ($passwordChanged) {
            $updateData['password'] = Hash::make($data['password']);
            $updateData['password_changed_at'] = now();
        }

        $user->update($updateData);

        if ($passwordChanged) {
            $this->revokeApiSessions($user, 'Profile password changed; API sessions revoked.');
        }

        return $this->profile($user->fresh());
    }

    /**
     * Keep the account photo synchronized with the role-specific entity.
     */
    public function updatePhoto(User $user, UploadedFile $photo): User
    {
        $user->loadMissing(['role', 'employee', 'company']);

        $files = app(SecureFileService::class);
        $newPath = $files->store($photo, 'profiles');

        $oldPaths = array_values(array_unique(array_filter([
            $user->profile_photo,
            $user->role?->code === 'EMPLOYEE' ? $user->employee?->photo : null,
            $user->role?->code === 'SUPER_ADMIN' ? $user->company?->logo : null,
        ])));

        $user->update(['profile_photo' => $newPath]);

        if ($user->role?->code === 'EMPLOYEE' && $user->employee) {
            $user->employee->update(['photo' => $newPath]);
        }

        if ($user->role?->code === 'SUPER_ADMIN' && $user->company) {
            $user->company->update(['logo' => $newPath]);
        }

        foreach ($oldPaths as $oldPath) {
            if ($oldPath !== $newPath) {
                $files->delete($oldPath);
            }
        }

        return $this->profile($user->fresh());
    }

    public function changePassword(User $user, array $data): void
    {
        $this->assertCurrentPassword($user, $data['current_password']);

        $user->update([
            'password' => Hash::make($data['password']),
            'password_changed_at' => now(),
        ]);

        $this->revokeApiSessions($user, 'Password changed; API sessions revoked.');
    }

    private function assertCurrentPassword(User $user, string $currentPassword): void
    {
        if (Hash::check($currentPassword, $user->password)) {
            return;
        }

        throw ValidationException::withMessages([
            'current_password' => 'Password lama tidak sesuai.',
        ]);
    }

    private function revokeApiSessions(User $user, string $message): void
    {
        $user->tokens()->delete();
        $user->forceFill(['fcm_token' => null])->save();

        Log::notice($message, [
            'user_id' => $user->id,
            'company_id' => $user->company_id,
        ]);
    }
}
