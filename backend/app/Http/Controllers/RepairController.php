<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Repair;
use App\Models\RepairLog;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index(Request $request)
    {
        $query = Repair::with(['equipment', 'reporter', 'assignee']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $repairs = $query->latest()->get();

        return ApiResponse::success($repairs);
    }

    public function show($id)
    {
        $repair = Repair::with(['equipment', 'reporter', 'assignee', 'logs.user'])->findOrFail($id);

        return ApiResponse::success($repair);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'title'        => 'required|string|max:200',
            'description'  => 'required|string',
            'priority'     => 'required|in:high,medium,low',
            'reported_by'  => 'required|exists:users,id',
            'assigned_to'  => 'nullable|exists:users,id',
        ]);

        $repair = Repair::create(array_merge($validated, [
            'status'      => 'pending',
            'reported_at' => now(),
        ]));

        RepairLog::create([
            'repair_id'  => $repair->id,
            'user_id'    => $validated['reported_by'],
            'action'     => '建立報修單',
            'note'       => $validated['description'],
            'created_at' => now(),
        ]);

        return ApiResponse::success($repair->load(['equipment', 'reporter']), '報修單已建立');
    }

    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $validated = $request->validate([
            'equipment_id' => 'sometimes|exists:equipment,id',
            'title'        => 'sometimes|string|max:200',
            'description'  => 'sometimes|string',
            'priority'     => 'sometimes|in:high,medium,low',
            'assigned_to'  => 'nullable|exists:users,id',
            'status'       => 'sometimes|in:pending,processing,done,cancelled',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'done' && !$repair->completed_at) {
            $validated['completed_at'] = now();
        }

        $repair->update($validated);

        return ApiResponse::success($repair->load(['equipment', 'reporter', 'assignee']), '報修單已更新');
    }

    public function updateStatus(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,done,cancelled',
        ]);

        $oldStatus = $repair->status;
        $repair->status = $request->status;

        if ($request->status === 'done' && !$repair->completed_at) {
            $repair->completed_at = now();
        }

        $repair->save();

        RepairLog::create([
            'repair_id'  => $repair->id,
            'user_id'    => $request->user()->id,
            'action'     => '狀態變更',
            'note'       => "狀態從「{$oldStatus}」更新為「{$request->status}」",
            'created_at' => now(),
        ]);

        return ApiResponse::success($repair, '報修狀態已更新');
    }

    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return ApiResponse::success(null, '報修單已刪除');
    }

    public function logs($id)
    {
        $repair = Repair::findOrFail($id);
        $logs = $repair->logs()->with('user')->orderBy('created_at')->get();

        return ApiResponse::success($logs);
    }

    public function addLog(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $validated = $request->validate([
            'action' => 'required|string|max:100',
            'note'   => 'nullable|string',
        ]);

        $log = RepairLog::create([
            'repair_id'  => $repair->id,
            'user_id'    => $request->user()->id,
            'action'     => $validated['action'],
            'note'       => $validated['note'] ?? null,
            'created_at' => now(),
        ]);

        return ApiResponse::success($log->load('user'), '操作紀錄已新增');
    }
}
