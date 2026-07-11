<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // 取得統計資料
    public function stats()
    {
        $now   = Carbon::now();
        $today = Carbon::today();

        $total      = Repair::count();
        $pending    = Repair::where('status', 'pending')->count();
        $processing = Repair::where('status', 'processing')->count();
        $done       = Repair::whereIn('status', ['done', 'completed'])->count();
        $todayNew   = Repair::whereDate('created_at', $today)->count();

        // 逾期：超過 3 天未完成
        $overdue = Repair::whereNotIn('status', ['done', 'completed', 'cancelled'])
            ->where('reported_at', '<', $now->subDays(3))
            ->count();

        // 平均處理時間（小時）
        $avgHours = Repair::whereNotNull('completed_at')
            ->selectRaw('AVG(JULIANDAY(completed_at) - JULIANDAY(reported_at)) * 24 as avg_hours')
            ->value('avg_hours');

        return response()->json([
            'total'               => $total,
            'pending'             => $pending,
            'processing'          => $processing,
            'done'                => $done,
            'today_new'           => $todayNew,
            'overdue'             => $overdue,
            'avg_processing_time' => round($avgHours ?? 0),
            'equipment_total'     => Equipment::count(),
        ]);
    }

    // 取得最新 5 筆報修案件
    public function latestRepairs()
    {
        $repairs = Repair::with(['equipment'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($repairs);
    }
}