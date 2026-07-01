<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
 protected $fillable=[
        'name',
        'type',
        'serial_number',
        'status',
        'location',
        'purchase_date',
        'description',
 ];

 public function repairs()
 {
    return $this->hasMany(Repair::class);
 }
}
