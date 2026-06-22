<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Factory;
use App\Models\Production;
use Carbon\Carbon;
use App\Models\Machine;   
use App\Models\Employee;
class FactoryController extends Controller
{
    // 1. Get all factories
    public function index()
    {
        $factories = Factory::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $factories
        ]);
    }

    // 2. Insert factory
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

    // 3. Edit (Get single factory)
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

    // 4. Update factory
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

    // 5. Delete factory
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

    $productions = Production::where('factory_id', $id)->get();

    // ✅ Variety ke hisaab se group karo aur ready_production sum karo
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

        "today_units" => $productions
            ->where('created_at', '>=', now()->startOfDay())
            ->sum('total_length'),

        "weekly_units" => $productions
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('total_length'),

        "total_varieties" => $productions->pluck('variety_type')->unique()->count(),

        "machines_count"  => Machine::where('factory_id', $id)->count(),
        "employees_count" => $productions->pluck('employee_id')->unique()->count(),

        // ✅ Ab varieties grouped data hai — sirf names nahi
        "varieties" => $varietiesGrouped,
    ]);
}
}