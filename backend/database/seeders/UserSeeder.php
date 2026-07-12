<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'       => '系統管理員',
            'email'      => 'admin@equipflow.com',
            'password'   => Hash::make('password123'),
            'role'       => 'admin',
            'department' => '資訊部',
        ]);

        User::create([
            'name'       => '王小明',
            'email'      => 'staff@equipflow.com',
            'password'   => Hash::make('password123'),
            'role'       => 'staff',
            'department' => '總務部',
        ]);

        User::create([
            'name'       => '李維修',
            'email'      => 'tech@equipflow.com',
            'password'   => Hash::make('password123'),
            'role'       => 'technician',
            'department' => '維修部',
        ]);
    }
}