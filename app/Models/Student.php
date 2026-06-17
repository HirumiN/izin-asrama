<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'nim', 'dorm_room', 'phone', 'is_suspended', 'suspended_at'])]
class Student extends Model
{
    protected $casts = [
        'is_suspended' => 'boolean',
        'suspended_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function permits(): HasMany
    {
        return $this->hasMany(Permit::class);
    }

    /**
     * Cek apakah mahasiswa sedang ditangguhkan.
     */
    public function isSuspended(): bool
    {
        return (bool) $this->is_suspended;
    }
}
