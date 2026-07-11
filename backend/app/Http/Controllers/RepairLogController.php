<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\RepairLog;
use Illuminate\Http\Request;

class RepairLogController extends Controller
{
    // 取得指定報修單的紀錄
    public function index($repairId)
    {
        $repair = Repair::findOrFail($repairId);
        $logs   = $repair->logs()->orderBy('created_at', 'desc')->get();

        return response()->json($logs);
    }

    // 新增報修紀錄
    public function store(Request $request, $repairId)
    {
        Repair::findOrFail($repairId);

        $request->validate([
            'action' => 'required|string|max:100',
            'note'   => 'nullable|string',
        ]);

        $log = RepairLog::create([
            'repair_id'  => $repairId,
            'user_id'    => $request->user_id ?? 1,
            'action'     => $request->action,
            'note'       => $request->note,
            'created_at' => now(),
        ]);

        return response()->json([
            'message' => '紀錄新增成功',
            'log'     => $log,
        ], 201);
    }
}