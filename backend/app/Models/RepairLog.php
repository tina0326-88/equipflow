<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RepairLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'repair_id',
        'user_id',
        'action',
        'note',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // 關聯報修單
    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    // 操作人員
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}