<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyWorkSchedule extends Model
{
    protected $fillable = [
        'company_id', 'monday', 'tuesday', 'wednesday', 'thursday',
        'friday', 'saturday', 'sunday',
    ];

    protected $casts = [
        'monday' => 'boolean', 'tuesday' => 'boolean', 'wednesday' => 'boolean',
        'thursday' => 'boolean', 'friday' => 'boolean', 'saturday' => 'boolean',
        'sunday' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
