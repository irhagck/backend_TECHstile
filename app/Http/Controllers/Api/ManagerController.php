<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\Production;
use App\Models\Machine;
use App\Models\Employee;
use Illuminate\Http\Request;

class ManagerController extends Controller
{

    // DASHBOARD BY MANAGER ID
    public function dashboard($managerId)
    {

        // manager ki factory production se find
        $factoryId = Production::where('manager_id',$managerId)
            ->value('factory_id');


        if(!$factoryId){
            return response()->json([
                "message"=>"No factory assigned"
            ],404);
        }


        $factory = Factory::find($factoryId);



        $productions = Production::where('manager_id',$managerId)
            ->get();



        $varieties = $productions
        ->groupBy('variety_type')
        ->map(function($item,$name){

            return [

                "variety_type"=>$name,

                "ready_production"=>$item->sum('ready_production')
            ];

        })
        ->values();



        return response()->json([

            "status"=>true,

            "factory"=>$factory,


            "today_units"=>$productions
            ->where('created_at','>=',now()->startOfDay())
            ->sum('total_length'),


            "weekly_units"=>$productions
            ->where('created_at','>=',now()->subDays(7))
            ->sum('total_length'),


            "total_varieties"=>$varieties->count(),


            "machines_count"=>
            Machine::where('factory_id',$factoryId)->count(),


            "employees_count"=>
            Employee::where('factory_id',$factoryId)->count(),


            "varieties"=>$varieties

        ]);

    }




    // MACHINES

    public function machines($managerId)
    {


        $factoryId = Production::where('manager_id',$managerId)
        ->value('factory_id');


        $machines =
        Machine::where('factory_id',$factoryId)
        ->get();


        return response()->json([

            "status"=>true,

            "machines"=>$machines

        ]);

    }






    // EMPLOYEES

    public function employees($managerId)
    {


        $factoryId = Production::where('manager_id',$managerId)
        ->value('factory_id');



        $employees =
        Employee::where('factory_id',$factoryId)
        ->get();



        return response()->json([

            "status"=>true,

            "employees"=>$employees

        ]);

    }




}