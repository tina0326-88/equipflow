<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 建立測試使用者
        User::create([
            'name'       => '管理員',
            'email'      => 'admin@example.com',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'department' => '資訊部',
        ]);

        User::create([
            'name'       => '陳小明',
            'email'      => 'staff@example.com',
            'password'   => Hash::make('password'),
            'role'       => 'staff',
            'department' => '總務部',
        ]);

        User::create([
            'name'       => '李維修',
            'email'      => 'tech@example.com',
            'password'   => Hash::make('password'),
            'role'       => 'technician',
            'department' => '設備維修部',
        ]);

        $this->call([
            EquipmentSeeder::class,
            RepairSeeder::class,
        ]);
    }
}
