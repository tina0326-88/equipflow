<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 負責的報修單
    public function repairs()
    {
        return $this->hasMany(Repair::class, 'reported_by');
    }

    // 指派的報修單
    public function assignedRepairs()
    {
        return $this->hasMany(Repair::class, 'assigned_to');
    }

    // 操作紀錄
    public function repairLogs()
    {
        return $this->hasMany(RepairLog::class);
    }
}
