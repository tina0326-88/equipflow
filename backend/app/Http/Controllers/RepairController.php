<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\RepairLog;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    // 取得報修列表
    public function index(Request $request)
    {
        $query = Repair::with(['equipment', 'reporter', 'assignee']);

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(10));
    }

    // 取得單一報修單
    public function show($id)
    {
        $repair = Repair::with(['equipment', 'reporter', 'assignee', 'logs'])
            ->findOrFail($id);

        return response()->json($repair);
    }

    // 新增報修單
    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:200',
            'description'  => 'required|string',
            'equipment_id' => 'required|exists:equipment,id',
            'priority'     => 'required|in:high,medium,low',
            'reported_by'  => 'required|exists:users,id',
        ]);

        $repair = Repair::create([
            ...$request->all(),
            'status'      => 'pending',
            'reported_at' => now(),
        ]);

        // 自動建立報修紀錄
        RepairLog::create([
            'repair_id'  => $repair->id,
            'user_id'    => $request->reported_by,
            'action'     => '建立報修單',
            'note'       => $request->description,
            'created_at' => now(),
        ]);

        return response()->json([
            'message' => '報修單新增成功',
            'repair'  => $repair,
        ], 201);
    }

    // 更新報修單
    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $request->validate([
            'title'        => 'string|max:200',
            'description'  => 'string',
            'equipment_id' => 'exists:equipment,id',
            'priority'     => 'in:high,medium,low',
            'assigned_to'  => 'nullable|exists:users,id',
        ]);

        $repair->update($request->all());

        return response()->json([
            'message' => '報修單更新成功',
            'repair'  => $repair,
        ]);
    }

    // 更新報修狀態
    public function updateStatus(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,done,cancelled',
        ]);

        $oldStatus = $repair->status;
        $repair->status = $request->status;

        if ($request->status === 'done') {
            $repair->completed_at = now();
        }

        $repair->save();

        // 自動建立狀態變更紀錄
        RepairLog::create([
            'repair_id'  => $repair->id,
            'user_id'    => $request->user_id ?? 1,
            'action'     => '狀態變更',
            'note'       => "狀態從「{$oldStatus}」變更為「{$request->status}」",
            'created_at' => now(),
        ]);

        return response()->json([
            'message' => '狀態更新成功',
            'repair'  => $repair,
        ]);
    }

    // 刪除報修單
    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return response()->json([
            'message' => '報修單刪除成功',
        ]);
    }
}