<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\Production;
use App\Models\Machine;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ManagerController extends Controller
{

    public function dashboard(Request $request, $factoryId)
    {
        $factory = Factory::find($factoryId);

        if (!$factory) {
            return response()->json([
                "message" => "Factory not found"
            ], 404);
        }

        // ✅ Saari productions (har status) fetch karo — history ke liye sirf owner-approved
        //    (status 4) use hongi, lekin Today / This Week ke liye pipeline breakdown bhi chahiye
        //    (employee ne kitna add kiya, manager ne kitna approve kiya, owner ne kitna approve kiya).
        $allProductions = Production::where('factory_id', $factoryId)->get();

        // History/period totals hamesha sirf OWNER-APPROVED (status 4) par based hain
        $productions = $allProductions->where('status', 4);

        // 0 = Sunday ... 6 = Saturday (Carbon's dayOfWeek numbering). Default Monday.
        $weekStartDay = (int) ($factory->week_start_day ?? 1);

        $rawPeriod = strtolower(trim((string) $request->query('period', $request->query('days', 'this_week'))));

        // Backward-compatible aliases for the old query values
        $aliasMap = [
            'day' => 'today', '1' => 'today', '1day' => 'today', '1_day' => 'today',
            'week' => 'this_week', '7' => 'this_week', '7day' => 'this_week', '7days' => 'this_week', '7_days' => 'this_week',
            'month' => 'this_month', '30' => 'this_month', '30day' => 'this_month', '30days' => 'this_month', '30_days' => 'this_month',
            'year' => 'this_year', '365' => 'this_year', '365day' => 'this_year', '365days' => 'this_year', '365_days' => 'this_year',
        ];
        $period = $aliasMap[$rawPeriod] ?? $rawPeriod;

        [$rangeStart, $rangeEnd, $periodLabel] = $this->resolvePeriodRange($period, $weekStartDay);

        // Filter productions that fall inside the resolved range
        $periodProductions = $productions->filter(function ($p) use ($rangeStart, $rangeEnd) {
            return $p->created_at >= $rangeStart && $p->created_at <= $rangeEnd;
        });

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

        $todayStart = Carbon::today();
        $todayEnd   = Carbon::today()->endOfDay();

        // "Today" stats are always today's — independent of the selected filter
        $todayUnits = $productions
            ->where('created_at', '>=', $todayStart)
            ->where('created_at', '<=', $todayEnd)
            ->sum('ready_production');

        $periodUnits = $periodProductions->sum('ready_production');

        // ✅ Pipeline breakdown — sirf Today aur This Week ke liye (history me sirf approved dikhta hai)
        $todayBreakdown = $this->pipelineBreakdown($allProductions, $todayStart, $todayEnd);
        $periodBreakdown = ($period === 'this_week')
            ? $this->pipelineBreakdown($allProductions, $rangeStart, $rangeEnd)
            : null;

        return response()->json([
            "status"          => true,
            "factory"         => $factory,

            "week_start_day"      => $weekStartDay,
            "week_start_day_name" => Carbon::now()->startOfWeek(0)->addDays($weekStartDay)->format('l'),

            "selected_period" => $periodLabel,   // e.g. "This Week"
            "period_key"      => $period,        // e.g. "this_week"
            "range_label"     => $this->formatRangeLabel($rangeStart, $rangeEnd, $period),
            "range_start"     => $rangeStart->toDateString(),
            "range_end"       => $rangeEnd->toDateString(),

            "today_date"      => Carbon::today()->toDateString(),
            "today_day_name"  => Carbon::today()->format('l'),

            // ✅ "ready_production" = asal ban chuki (owner-approved) production
            "today_units"     => $todayUnits,
            "period_units"    => $periodUnits,
            "weekly_units"    => $periodUnits, // For backward compatibility with existing views

            // ✅ Employee-added / Manager-approved / Owner-approved breakdown
            "today_breakdown"  => $todayBreakdown,
            "period_breakdown" => $periodBreakdown,

            "total_varieties" => $varietiesGrouped->count(),

            "machines_count" => Machine::where(
                'factory_id',
                $factoryId
            )->count(),

            "employees_count" => Employee::where(
                'factory_id',
                $factoryId
            )->count(),

            "varieties" => $varietiesGrouped,
        ]);
    }

    // ✅ Manager bhi apni factory ka "week" kis din se start hota hai woh set/update kar sake
    public function updateWeekStartDay(Request $request, $factoryId)
    {
        $factory = Factory::find($factoryId);

        if (!$factory) {
            return response()->json(['status' => false, 'message' => 'Factory not found'], 404);
        }

        $request->validate([
            // 0 = Sunday ... 6 = Saturday
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

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Splits productions inside a date range into 3 separate (non-overlapping) buckets
     * by their current status — used only for Today / This Week, not for older history:
     *   1 = employee submitted, abhi kisi ne review nahi kiya
     *   2 = manager ne approve kar diya, owner ka approval abhi baaki hai
     *   4 = owner ne final approve kar diya
     * (status 3/5 = rejected, in me shamil nahi kiya jata)
     */
    private function pipelineBreakdown($allProductions, Carbon $start, Carbon $end): array
    {
        $inRange = $allProductions->filter(function ($p) use ($start, $end) {
            return $p->created_at >= $start && $p->created_at <= $end;
        });

        return [
            'employee_added'   => $inRange->where('status', 1)->sum('ready_production'),
            'manager_approved' => $inRange->where('status', 2)->sum('ready_production'),
            'owner_approved'   => $inRange->where('status', 4)->sum('ready_production'),
        ];
    }

    /**
     * Resolve [$start, $end, $label] Carbon range for a given period key.
     * $weekStartDay: 0 (Sun) .. 6 (Sat) — the factory's configured week start.
     */
    private function resolvePeriodRange(string $period, int $weekStartDay): array
    {
        $today = Carbon::today();

        // How many days back is the start of the CURRENT week from today
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

        // Same month & year → "Sat 29 – Tue 01 Sep"
        if ($start->isSameMonth($end)) {
            return $start->format('D d') . ' – ' . $end->format('D d M Y');
        }

        return $start->format('D d M') . ' – ' . $end->format('D d M Y');
    }

    // EMPLOYEES METHOD 
    public function employees($factoryId)
    {
        $factory = Factory::find($factoryId);

        if (!$factory) {
            return response()->json([
                "message" => "Factory not found"
            ], 404);
        }

        $employees = Employee::with('user')
            ->where('factory_id', $factoryId)
            ->get();

        //  fetch active employee IDs from Attendence table duration of last 24 hours
        $activeEmployeeIds = Attendence::where('type', 'IN')
            ->where('created_at', '>=', now()->subHours(12))
            ->pluck('employee_id')
            ->unique();

        // set is_active flag for each employee based on activeEmployeeIds
        $employees = $employees->map(function ($e) use ($activeEmployeeIds) {
            $arr = $e->toArray();
            $arr['is_active'] = $activeEmployeeIds->contains($e->id);
            return $arr;
        });

        return response()->json([
            "status" => true,
            "employees" => $employees,
            "total_employees"  => $employees->count(),
            "active_employees" => $activeEmployeeIds->count(),
        ]);
    }

    // MACHINES METHOD 
    public function machines($factoryId)
    {
        $factory = Factory::find($factoryId);

        if (!$factory) {
            return response()->json([
                "message" => "Factory not found"
            ], 404);
        }

        $machines = Machine::where('factory_id', $factoryId)->get();

        // fetch active employee IDs from Attendence table duration of last 24 hours
        $activeEmployeeIds = Attendence::where('type', 'IN')
            ->where('created_at', '>=', now()->subHours(12))
            ->pluck('employee_id')
            ->unique();

        // fetch machine ids from Production table where employee_id is in activeEmployeeIds and factory_id is the given factoryId
        $activeMachineIds = Production::where('factory_id', $factoryId)
            ->whereIn('employee_id', $activeEmployeeIds)
            ->pluck('machine_id')
            ->unique();

        // mark is active flag for each machine based on activeMachineIds
        $machines = $machines->map(function ($m) use ($activeMachineIds) {
            $arr = $m->toArray();
            $arr['is_active'] = $activeMachineIds->contains($m->id);
            return $arr;
        });

        return response()->json([
            "status" => true,
            "machines" => $machines,
            "total_machines"  => $machines->count(),
            "active_machines" => $activeMachineIds->count(),
        ]);
    }
     // EMPLOYEE DETAILS

    public function employeeDetails($employeeId)

    {

         \Log::info("Employee ID Received = ".$employeeId);

        $employee = Employee::with('user')

            ->find($employeeId);



        if (!$employee) {

            return response()->json([

                'message' => 'Employee not found'

            ], 404);

        }



        $productions = Production::where(

            'employee_id',

            $employee->id

        )->get();



        return response()->json([



            'employee_id' => $employee->id,



            'name' => $employee->user?->name,



            'email' => $employee->user?->email,



            'shift_start' => $employee->shift_starttime,



            'shift_end' => $employee->shift_endtime,



            'total_production' => $productions->sum('ready_production'),



            'total_waste' => $productions->sum('waste_production'),



            'machines_worked' => $productions

                ->pluck('machine_id')

                ->unique()

                ->count(),



            'total_entries' => $productions->count(),



            'created_at' => $employee->created_at,

        ]);

    }

    //manager profile

    public function profile($userId)

{

    $user = User::find($userId);



    if (!$user) {

        return response()->json([

            'status' => false,

            'message' => 'Manager not found'

        ], 404);

    }



    $factoryId = Production::where(

        'manager_id',

        $userId

    )->value('factory_id');



    $totalEmployees = Employee::where(

        'factory_id',

        $factoryId

    )->count();



    $totalProduction = Production::where(

        'factory_id',

        $factoryId

    )->sum('ready_production');



    $factoryName = Factory::where('id', $factoryId)->value('name');



    return response()->json([

        'status' => true,

        'data' => [

            'id' => $user->id,

            'name' => $user->name,

            'email' => $user->email,

            'phone_no' => $user->phone_no,

            'address' => $user->address,

            'pic' => $user->pic,

            'factory_id' => $factoryId,

            'factory_name' => $factoryName,

            'total_employees' => $totalEmployees,

            'total_production' => $totalProduction,

        ]

    ]);

}
  // ==========================
    // PAYMENTS / PRODUCTIONS
    // ==========================
    public function payments($factoryId)
    {
        $factory = Factory::find($factoryId);

        if (!$factory) {
            return response()->json([
                "message" => "Factory not found"
            ], 404);
        }

        $productions = Production::with(['employeedetails.user', 'machineemploye'])
            ->where('factory_id', $factoryId)
            ->latest()
            ->get();

        return response()->json([
            "status" => true,
            "productions" => $productions,
        ]);
    }
}