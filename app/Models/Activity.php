<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'date', 'start_time', 'end_time', 'description'])]
class Activity extends Model
{
    protected $casts = [
        'date' => 'date',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(ActivityAttendance::class);
    }
}
