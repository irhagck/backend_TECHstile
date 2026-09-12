<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Factory;
use App\Models\Production;
use App\Models\Machine;
use App\Models\User;
use App\Models\Attendence;
use Carbon\Carbon;
use App\Models\Employee;

class FactoryController extends Controller
{
    // Get all factories
    public function index()
    {
        $factories = Factory::latest()->get();

        $cutoff = now()->subHours(12);

        // Pichle 12 hours mein jin employees ne attendance mark ki
        $recentEmployeeIds = Attendence::where('created_at', '>=', $cutoff)
            ->pluck('employee_id')
            ->unique();

        $activeFactoryIds = collect();

        if ($recentEmployeeIds->isNotEmpty()) {
            // In employees ka kis kis factory se link hai (Production table ke zariye)
            $activeFactoryIds = Production::whereIn('employee_id', $recentEmployeeIds)
                ->pluck('factory_id')
                ->unique();
        }

        $factories = $factories->map(function ($f) use ($activeFactoryIds) {
            $arr = $f->toArray();
            $arr['is_active'] = $activeFactoryIds->contains($f->id);
            return $arr;
        });

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

    public function dashboard(Request $request, $id)
    {
        $factory = Factory::find($id);

        if (!$factory) {
            return response()->json(['message' => 'Factory not found'], 404);
        }

        $allProductions = Production::where('factory_id', $id)->get();

        $productions = $allProductions->where('status', 4);

        $weekStartDay = (int) ($factory->week_start_day ?? 1);

        $rawPeriod = strtolower(trim((string) $request->query('period', $request->query('days', 'this_week'))));

        $aliasMap = [
            'day' => 'today', '1' => 'today', '1day' => 'today', '1_day' => 'today',
            'week' => 'this_week', '7' => 'this_week', '7day' => 'this_week', '7days' => 'this_week', '7_days' => 'this_week',
            'month' => 'this_month', '30' => 'this_month', '30day' => 'this_month', '30days' => 'this_month', '30_days' => 'this_month',
            'year' => 'this_year', '365' => 'this_year', '365day' => 'this_year', '365days' => 'this_year', '365_days' => 'this_year',
        ];
        $period = $aliasMap[$rawPeriod] ?? $rawPeriod;

        [$rangeStart, $rangeEnd, $periodLabel] = $this->resolvePeriodRange($period, $weekStartDay);

        $periodProductions = $productions->filter(function ($p) use ($rangeStart, $rangeEnd) {
            return $p->created_at >= $rangeStart && $p->created_at <= $rangeEnd;
        });

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

        $todayStart = Carbon::today();
        $todayEnd   = Carbon::today()->endOfDay();

        $todayUnits = $productions
            ->where('created_at', '>=', $todayStart)
            ->where('created_at', '<=', $todayEnd)
            ->sum('ready_production');

        $periodUnits = $periodProductions->sum('ready_production');

        $todayBreakdown = $this->pipelineBreakdown($allProductions, $todayStart, $todayEnd);
        $periodBreakdown = ($period === 'this_week')
            ? $this->pipelineBreakdown($allProductions, $rangeStart, $rangeEnd)
            : null;

        return response()->json([
            "status"          => true,
            "factory"         => $factory,

            "week_start_day"      => $weekStartDay,
            "week_start_day_name" => Carbon::now()->startOfWeek(0)->addDays($weekStartDay)->format('l'),

            "selected_period" => $periodLabel,
            "period_key"      => $period,
            "range_label"     => $this->formatRangeLabel($rangeStart, $rangeEnd, $period),
            "range_start"     => $rangeStart->toDateString(),
            "range_end"       => $rangeEnd->toDateString(),

            "today_date"      => Carbon::today()->toDateString(),
            "today_day_name"  => Carbon::today()->format('l'),

            "today_units"     => $todayUnits,
            "period_units"    => $periodUnits,
            "weekly_units"    => $periodUnits,

            "today_breakdown"  => $todayBreakdown,
            "period_breakdown" => $periodBreakdown,

            "total_varieties" => $varietiesGrouped->count(),
            "machines_count"  => Machine::where('factory_id', $id)->count(),

            "employees_count" => Employee::where('factory_id', $id)->count(),

            "varieties"       => $varietiesGrouped,
        ]);
    }

    public function updateWeekStartDay(Request $request, $id)
    {
        $factory = Factory::find($id);

        if (!$factory) {
            return response()->json(['status' => false, 'message' => 'Factory not found'], 404);
        }

        $request->validate([
            'week_start_day' => 'required|integer|min:0|max:6',
        ]);

        $factory->week_start_day = $request->week_start_day;
        $factory->save();

        return response()->json([
            'status'  => true,
            'message' => 'Week start day updated successfully',
            'week_start_day' => $factory->week_start_day,
            'week_start_day_name' => Carbon::now()->startOfWeek(0)->addDays($factory->week_start_day)->format('l'),
        ]);
    }

    private function pipelineBreakdown($allProductions, Carbon $start, Carbon $end): array
    {
        $inRange = $allProductions->filter(function ($p) use ($start, $end) {
            return $p->created_at >= $start && $p->created_at <= $end;
        });

        return [
            'employee_added'   => $inRange->where('status', 1)->sum('ready_production'),
            'manager_approved' => $inRange->where('status', 2)->sum('ready_production'),
        ];
    }

    private function resolvePeriodRange(string $period, int $weekStartDay): array
    {
        $today = Carbon::today();

        $diffToCurrentWeekStart = ($today->dayOfWeek - $weekStartDay + 7) % 7;
        $currentWeekStart = $today->copy()->subDays($diffToCurrentWeekStart)->startOfDay();
        $currentWeekEnd   = $currentWeekStart->copy()->addDays(6)->endOfDay();

        switch ($period) {
            case 'today':
                return [$today->copy()->startOfDay(), $today->copy()->endOfDay(), 'Today'];

            case 'previous_week':
                $start = $currentWeekStart->copy()->subDays(7);
                $end   = $start->copy()->addDays(6)->endOfDay();
                return [$start, $end, 'Previous Week'];

            case 'this_month':
                return [$today->copy()->startOfMonth(), $today->copy()->endOfMonth(), 'This Month'];

            case 'previous_month':
                $start = $today->copy()->subMonth()->startOfMonth();
                $end   = $start->copy()->endOfMonth();
                return [$start, $end, 'Previous Month'];

            case 'this_year':
                return [$today->copy()->startOfYear(), $today->copy()->endOfYear(), 'This Year'];

            case 'previous_year':
                $start = $today->copy()->subYear()->startOfYear();
                $end   = $start->copy()->endOfYear();
                return [$start, $end, 'Previous Year'];

            case 'this_week':
            default:
                return [$currentWeekStart, $currentWeekEnd->copy()->min($today->copy()->endOfDay()), 'This Week'];
        }
    }

    private function formatRangeLabel(Carbon $start, Carbon $end, string $period): string
    {
        if ($period === 'today') {
            return $start->format('D, d M Y');
        }

        if ($start->isSameMonth($end)) {
            return $start->format('D d') . ' – ' . $end->format('D d M Y');
        }

        return $start->format('D d M') . ' – ' . $end->format('D d M Y');
    }
}