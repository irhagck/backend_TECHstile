<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production;

class ProductionController extends Controller
{
    // 1. Show all productions
public function index() 
{
    // Eager loading multiple relationships:
    // 1. factory (Factory name ke liye)
    // 2. employee.user (User name ke liye)
    // 3. machine (Machine ID/Type ke liye)
    $productions = Production::with(['factory', 'employee.user', 'machine'])->get();

    return response()->json($productions, 200);
}
    // public function index()
    // {
    //     return response()->json(Production::all(), 200);
    // }

    // 2. Add production
    public function store(Request $request)
    {
        $production = Production::create($request->all());

        return response()->json([
            'message' => 'Production created successfully',
            'data' => $production
        ], 201);
    }

    // 3. Show single production
    public function edit($id)
    {
        $production = Production::find($id);

        if (!$production) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($production, 200);
    }

    // 4. Update production
    public function update(Request $request, $id)
    {
        $production = Production::find($id);

        if (!$production) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $production->update($request->all());

        return response()->json([
            'message' => 'Production updated successfully',
            'data' => $production
        ], 200);
    }

    // 5. Delete production
    public function destroy($id)
    {
        $production = Production::find($id);

        if (!$production) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $production->delete();

        return response()->json([
            'message' => 'Production deleted successfully'
        ], 200);
    }
}