<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    protected $fillable = [
        'name',
        'type',
        'serial_number',
        'status',
        'location',
        'purchase_date',
        'description',
    ];

    // 一個設備有多個報修單
    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }
}