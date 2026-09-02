<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendence;

class AttendenceController extends Controller
{
    //Show all attendance
    public function index()
    {
        return response()->json(Attendence::all(), 200);
    }

    //Add attendance
    public function store(Request $request)
    {
        $attendence = Attendence::create($request->all());

        return response()->json([
            'message' => 'Attendance created successfully',
            'data' => $attendence
        ], 201);
    }

    // Edit 
    public function edit($id)
    {
        $attendence = Attendence::find($id);

        if (!$attendence) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($attendence, 200);
    }

    //Update attendance
    public function update(Request $request, $id)
    {
        $attendence = Attendence::find($id);

        if (!$attendence) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $attendence->update($request->all());

        return response()->json([
            'message' => 'Attendance updated successfully',
            'data' => $attendence
        ], 200);
    }

    //Delete attendance
    public function destroy($id)
    {
        $attendence = Attendence::find($id);

        if (!$attendence) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $attendence->delete();

        return response()->json([
            'message' => 'Attendance deleted successfully'
        ], 200);
    }

// employee attendance function
   public function markAttendance(Request $request)
{
    $request->validate([
        'employee_id'=>'required|integer',
        'machine_id'=>'required|integer',
    ]);

    $attendance = Attendence::create([
        'employee_id'=>$request->employee_id,
        'machine_id'=>$request->machine_id,
        'type'=>'IN',
    ]);

    return response()->json([
        'message'=>'Attendance marked successfully',
        'data'=>$attendance
    ],201);
}
}