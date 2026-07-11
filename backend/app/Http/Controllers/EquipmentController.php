<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    // 取得設備列表
    public function index(Request $request)
    {
        $query = Equipment::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('serial_number', 'like', "%{$request->search}%")
                  ->orWhere('location', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(10));
    }

    // 取得單一設備
    public function show($id)
    {
        $equipment = Equipment::findOrFail($id);
        return response()->json($equipment);
    }

    // 新增設備
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:150',
            'type'          => 'required|string|max:100',
            'serial_number' => 'required|string|max:50|unique:equipment',
            'status'        => 'required|in:active,maintenance,broken',
            'location'      => 'required|string|max:150',
            'purchase_date' => 'nullable|date',
            'description'   => 'nullable|string',
        ]);

        $equipment = Equipment::create($request->all());

        return response()->json([
            'message'   => '設備新增成功',
            'equipment' => $equipment,
        ], 201);
    }

    // 更新設備
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $request->validate([
            'name'          => 'string|max:150',
            'type'          => 'string|max:100',
            'serial_number' => 'string|max:50|unique:equipment,serial_number,' . $id,
            'status'        => 'in:active,maintenance,broken',
            'location'      => 'string|max:150',
            'purchase_date' => 'nullable|date',
            'description'   => 'nullable|string',
        ]);

        $equipment->update($request->all());

        return response()->json([
            'message'   => '設備更新成功',
            'equipment' => $equipment,
        ]);
    }

    // 刪除設備
    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return response()->json([
            'message' => '設備刪除成功',
        ]);
    }
}