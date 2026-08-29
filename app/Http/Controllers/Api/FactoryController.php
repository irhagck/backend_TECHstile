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

    public function dashboard(Request $request, $id)
    {
        $factory = Factory::find($id);

        if (!$factory) {
            return response()->json(['message' => 'Factory not found'], 404);
        }

        // ✅ rejected productions (status 3 = manager rejected, 5 = owner rejected) ko exclude karo
        $productions = Production::where('factory_id', $id)
            ->whereNotIn('status', [3, 5])
            ->get();

        // Determine period filter (default: week)
        $rawPeriod = strtolower(trim((string)$request->query('period', $request->query('days', 'week'))));
        $startDate = null;
        $periodLabel = 'Week';

        switch ($rawPeriod) {
            case 'day':
            case 'today':
            case '1':
            case '1day':
            case '1_day':
                $startDate = Carbon::today();
                $periodLabel = 'Day';
                break;
            case 'week':
            case '7':
            case '7day':
            case '7days':
            case '7_days':
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                $periodLabel = 'Week';
                break;
            case 'month':
            case '30':
            case '30day':
            case '30days':
            case '30_days':
                $startDate = Carbon::now()->subDays(30)->startOfDay();
                $periodLabel = 'Month';
                break;
            case 'year':
            case '365':
            case '365day':
            case '365days':
            case '365_days':
                $startDate = Carbon::now()->subDays(365)->startOfDay();
                $periodLabel = 'Year';
                break;
            case 'all':
            case 'all_time':
                $startDate = null;
                $periodLabel = 'All';
                break;
            default:
                if (is_numeric($rawPeriod) && (int)$rawPeriod > 0) {
                    $days = (int)$rawPeriod;
                    $startDate = Carbon::now()->subDays($days)->startOfDay();
                    $periodLabel = "$days Days";
                } else {
                    $startDate = Carbon::now()->subDays(7)->startOfDay();
                    $periodLabel = 'Week';
                }
                break;
        }

        // Filter productions for selected period
        $periodProductions = $productions;
        if ($startDate !== null) {
            $periodProductions = $productions->filter(function ($p) use ($startDate) {
                return $p->created_at >= $startDate;
            });
        }

        // ✅ Variety ke hisaab se group karo aur ready_production sum karo
        $varietiesGrouped = $periodProductions
            ->filter(function ($p) {
                return !empty($p->variety_type);
            })
            ->groupBy('variety_type')
            ->map(function ($group, $varietyName) {
                return [
                    'variety_type'     => $varietyName,
                    'ready_production' => $group->sum('ready_production'),
                ];
            })
            ->values();

        $todayUnits = $productions
            ->where('created_at', '>=', Carbon::today())
            ->sum('ready_production');

        $periodUnits = $periodProductions->sum('ready_production');

        return response()->json([
            "status"          => true,
            "factory"         => $factory,
            "selected_period" => $periodLabel,

            // ✅ "ready_production" = asal ban chuki production
            "today_units"     => $todayUnits,
            "period_units"    => $periodUnits,
            "weekly_units"    => $periodUnits, // For backward compatibility with existing views

            "total_varieties" => $varietiesGrouped->count(),
            "machines_count"  => Machine::where('factory_id', $id)->count(),

            // ✅ Factory ke actual assigned employees count
            "employees_count" => Employee::where('factory_id', $id)->count(),

            // ✅ Varieties grouped data for the selected period
            "varieties"       => $varietiesGrouped,
        ]);
    }
}