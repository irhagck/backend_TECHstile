<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Variety;
use Illuminate\Http\Request;

class VarietyController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Variety::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:varieties,name',
        ]);

        $variety = Variety::create(['name' => trim($request->name)]);

        return response()->json([
            'success' => true,
            'message' => 'Variety added successfully',
            'data' => $variety,
        ], 201);
    }

    public function destroy($id)
    {
        $variety = Variety::find($id);

        if (!$variety) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $variety->delete();

        return response()->json(['success' => true, 'message' => 'Variety deleted successfully']);
    }
}