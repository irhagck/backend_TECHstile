<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Factory;
use App\Models\Production;
use App\Models\Machine;
use App\Models\User;
use Carbon\Carbon;  
use App\Models\Employee;
class FactoryController extends Controller
{
    // Get all factories
    public function index()
    {
        $factories = Factory::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $factories
        ]);
    }

    // Insert factory
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
        ]);

        $factory = Factory::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Factory created successfully',
            'data' => $factory
        ]);
    }

    // Edit (Get single factory)
    public function show($id)
    {
        $factory = Factory::find($id);

        if (!$factory) {
            return response()->json([
                'status' => false,
                'message' => 'Factory not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $factory
        ]);
    }

    // Update factory
    public function update(Request $request, $id)
    {
        $factory = Factory::find($id);

        if (!$factory) {
            return response()->json([
                'status' => false,
                'message' => 'Factory not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
        ]);

        $factory->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Factory updated successfully',
            'data' => $factory
        ]);
    }

    // Delete factory
    public function destroy($id)
    {
        $factory = Factory::find($id);

        if (!$factory) {
            return response()->json([
                'status' => false,
                'message' => 'Factory not found'
            ], 404);
        }

        $factory->delete();

        return response()->json([
            'status' => true,
            'message' => 'Factory deleted successfully'
        ]);
    }

    public function dashboard($id)
{
    $factory = Factory::find($id);

    if (!$factory) {
        return response()->json(['message' => 'Factory not found'], 404);
    }

    //  Rejected productions (status 3 = manager rejected, 5 = owner rejected) 
    $productions = Production::where('factory_id', $id)
        ->whereNotIn('status', [3, 5])
        ->get();

    // Group based on variety and then add ready_production 
    $varietiesGrouped = $productions
        ->groupBy('variety_type')
        ->map(function ($group, $varietyName) {
            return [
                'variety_type'     => $varietyName,
                'ready_production' => $group->sum('ready_production'),
                // 'total_length'     => $group->sum('total_length'),
            ];
        })
        ->values();

    return response()->json([
        "status"  => true,
        "factory" => $factory,

        //ready_production = ready production, and total_length only batch base
        "today_units" => $productions
            ->where('created_at', '>=', now()->startOfDay())
            ->sum('ready_production'),

        "weekly_units" => $productions
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('ready_production'),

        "total_varieties" => $productions->pluck('variety_type')->unique()->filter()->count(),

        "machines_count"  => Machine::where('factory_id', $id)->count(),

        //Count assigned employees, only that employee that enter production
        "employees_count" => Employee::where('factory_id', $id)->count(),

        // varieties grouped with ready_production
        "varieties" => $varietiesGrouped,
    ]);
}
}