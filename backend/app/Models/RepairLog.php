<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairLog extends Model
{
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

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
