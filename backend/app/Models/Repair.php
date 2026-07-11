<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'title',
        'description',
        'status',
        'priority',
        'reported_by',
        'assigned_to',
        'reported_at',
        'completed_at',
    ];

    protected $casts = [
        'reported_at'  => 'datetime',
        'completed_at' => 'datetime',
    ];

    // 關聯設備
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    // 報修人
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    // 指派處理人
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // 報修紀錄
    public function logs()
    {
        return $this->hasMany(RepairLog::class);
    }
}