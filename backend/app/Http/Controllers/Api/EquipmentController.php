<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        return Equipment::paginate(10);
    }

    public function show($id)
    {
        return Equipment::findOrFail($id);
    }

    public function store(Request $request)
    {
        $equipment = Equipment::create(
            $request->all()
        );

        return response()->json(
            $equipment,
            201
        );
    }

    public function update(
        Request $request,
        $id
    ) {

        $equipment = Equipment::findOrFail($id);

        $equipment->update(
            $request->all()
        );

        return response()->json($equipment);
    }

    public function destroy($id)
    {
        Equipment::destroy($id);

        return response()->json([
            'message' => 'Deleted'
        ]);
    }
}