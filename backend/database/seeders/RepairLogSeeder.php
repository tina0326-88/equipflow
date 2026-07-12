<?php

namespace Database\Seeders;

use App\Models\RepairLog;
use Illuminate\Database\Seeder;

class RepairLogSeeder extends Seeder
{
    public function run(): void
    {
        $logs = [
            [
                'repair_id'  => 1,
                'user_id'    => 2,
                'action'     => '建立報修單',
                'note'       => '空調噪音問題，需盡快處理',
                'created_at' => now()->subDays(4),
            ],
            [
                'repair_id'  => 1,
                'user_id'    => 1,
                'action'     => '狀態變更',
                'note'       => '已指派維修人員前往查看',
                'created_at' => now()->subDays(3),
            ],
            [
                'repair_id'  => 2,
                'user_id'    => 2,
                'action'     => '建立報修單',
                'note'       => '監控畫面出現雜訊，影響安全監控',
                'created_at' => now()->subDays(2),
            ],
            [
                'repair_id'  => 2,
                'user_id'    => 3,
                'action'     => '指派處理',
                'note'       => '已安排技術人員於明日處理',
                'created_at' => now()->subDays(1),
            ],
            [
                'repair_id'  => 3,
                'user_id'    => 1,
                'action'     => '建立報修單',
                'note'       => '灑水頭漏水情況嚴重',
                'created_at' => now()->subDays(5),
            ],
            [
                'repair_id'  => 3,
                'user_id'    => 3,
                'action'     => '狀態變更',
                'note'       => '已完成維修，更換新零件',
                'created_at' => now()->subDays(3),
            ],
        ];

        foreach ($logs as $log) {
            RepairLog::create($log);
        }
    }
}