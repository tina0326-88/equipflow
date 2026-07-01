<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $equipment = $query->latest()->get();

        return ApiResponse::success($equipment);
    }

    public function show($id)
    {
        $equipment = Equipment::findOrFail($id);

        return ApiResponse::success($equipment);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'type'          => 'required|string|max:100',
            'serial_number' => 'required|string|max:50|unique:equipment,serial_number',
            'status'        => 'in:active,maintenance,broken',
            'location'      => 'required|string|max:150',
            'purchase_date' => 'nullable|date',
            'description'   => 'nullable|string',
        ]);

        $equipment = Equipment::create($validated);

        return ApiResponse::success($equipment, '設備已新增');
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'sometimes|string|max:150',
            'type'          => 'sometimes|string|max:100',
            'serial_number' => "sometimes|string|max:50|unique:equipment,serial_number,{$id}",
            'status'        => 'sometimes|in:active,maintenance,broken',
            'location'      => 'sometimes|string|max:150',
            'purchase_date' => 'nullable|date',
            'description'   => 'nullable|string',
        ]);

        $equipment->update($validated);

        return ApiResponse::success($equipment, '設備已更新');
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return ApiResponse::success(null, '設備已刪除');
    }
}
