<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Repair;
use App\Models\Equipment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function stats()
    {
        $overdueThreshold = Carbon::now()->subDays(3);

        $overdueCount = Repair::whereIn('status', ['pending', 'processing'])
            ->where('reported_at', '<', $overdueThreshold)
            ->count();

        $todayCount = Repair::whereDate('created_at', Carbon::today())->count();

        return ApiResponse::success([
            'total_repairs'    => Repair::count(),
            'pending'          => Repair::where('status', 'pending')->count(),
            'processing'       => Repair::where('status', 'processing')->count(),
            'done'             => Repair::where('status', 'done')->count(),
            'cancelled'        => Repair::where('status', 'cancelled')->count(),
            'overdue'          => $overdueCount,
            'today_new'        => $todayCount,
            'equipment_total'  => Equipment::count(),
            'equipment_active' => Equipment::where('status', 'active')->count(),
        ]);
    }

    public function latestRepairs()
    {
        $repairs = Repair::with(['equipment', 'reporter'])
            ->latest()
            ->take(5)
            ->get();

        return ApiResponse::success($repairs);
    }
}
