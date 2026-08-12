<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/*
|--------------------------------------------------------------------------
| Leave Quota
|--------------------------------------------------------------------------
|
| Jatah Cuti (BUKAN Sakit/Acara) milik satu employee untuk satu tahun.
| Baris di tabel ini hanya ada kalau company admin secara sengaja
| menyesuaikan jatah -- kalau tidak, employee tetap otomatis dapat
| default 12 hari/tahun. Lihat App\Services\LeaveQuotaService dan catatan
| lengkap di migration create_leave_quotas_table.
|
*/

class LeaveQuota extends Model
{
    protected $fillable = [

        'employee_id',

        'year',

        'total_days',

    ];

    protected $casts = [

        'year' => 'integer',

        'total_days' => 'integer',

    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class
        );
    }
}
