<?php

namespace Database\Seeders;

use App\Models\Repair;
use Illuminate\Database\Seeder;

class RepairSeeder extends Seeder
{
    public function run(): void
    {
        $repairs = [
            [
                'equipment_id' => 1,
                'title'        => '空調異常噪音',
                'description'  => '3樓會議室空調運作時有異常噪音',
                'status'       => 'pending',
                'priority'     => 'high',
                'reported_by'  => 2,
                'assigned_to'  => null,
                'reported_at'  => now()->subDays(4),
                'completed_at' => null,
            ],
            [
                'equipment_id' => 2,
                'title'        => '監控攝影機畫面異常',
                'description'  => 'B1停車場監控畫面出現雜訊',
                'status'       => 'processing',
                'priority'     => 'medium',
                'reported_by'  => 2,
                'assigned_to'  => 3,
                'reported_at'  => now()->subDays(2),
                'completed_at' => null,
            ],
            [
                'equipment_id' => 3,
                'title'        => '消防灑水頭漏水',
                'description'  => '5樓走廊消防灑水頭有漏水情況',
                'status'       => 'done',
                'priority'     => 'high',
                'reported_by'  => 1,
                'assigned_to'  => 3,
                'reported_at'  => now()->subDays(5),
                'completed_at' => now()->subDays(3),
            ],
            [
                'equipment_id' => 4,
                'title'        => '電梯按鈕故障',
                'description'  => '2號電梯3樓按鈕無反應',
                'status'       => 'pending',
                'priority'     => 'high',
                'reported_by'  => 2,
                'assigned_to'  => null,
                'reported_at'  => now()->subDays(1),
                'completed_at' => null,
            ],
            [
                'equipment_id' => 5,
                'title'        => '網路設備斷線',
                'description'  => '4樓網路交換器頻繁斷線',
                'status'       => 'processing',
                'priority'     => 'medium',
                'reported_by'  => 1,
                'assigned_to'  => 3,
                'reported_at'  => now()->subDays(1),
                'completed_at' => null,
            ],
        ];

        foreach ($repairs as $repair) {
            Repair::create($repair);
        }
    }
}