<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\FactoryController;
use App\Http\Controllers\Api\MachineController;
use App\Http\Controllers\Api\ProductionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AttendenceController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
Route::post('/login', [AuthController::class, 'login']);

Route::get('/test', function(){
    $user = User::create([
            'name' => 'írha',
            'email' => 'test@test.com',
            'password' => Hash::make('test@test.com'),
        ]);

        return response()->json([
            'message' => 'User created',
            'data' => $user
        ], 201);
});

Route::middleware('auth:sanctum')->get('/profile', [AuthController::class, 'profile']);
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::post('/login', [UserController::class, 'login']);
Route::get('/welcome2', [UserController::class, 'welcome2']);
//roles routes
// Route::middleware('auth:sanctum')->group(function () {
    
// });

Route::prefix('roles')->group(function () {
    Route::get('/all', [RoleController::class, 'index']);
   // ->middleware('permission:view roles');
    Route::post('/add', [RoleController::class, 'store']);
    //->middleware('permission:create roles');
    Route::get('/edit/{id}', [RoleController::class, 'show']);
    //->middleware('permission:view roles');
    Route::put('/update/{id}', [RoleController::class, 'update']);
    //->middleware('permission:update roles');
    Route::delete('/delete/{id}', [RoleController::class, 'destroy']);
    //->middleware('permission:delete roles');
});
//permissions routes
Route::get('permissions/all', [PermissionController::class, 'getAllPermissions']);
Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
Route::post('permissions/sync', [PermissionController::class, 'syncPermissions']);
// Factory Routes
Route::prefix('factories')->group(function () {

    Route::get('allfactories', [FactoryController::class, 'index']);
    Route::post('addfactory', [FactoryController::class, 'store']);
    Route::get('editfactory/{id}', [FactoryController::class, 'show']);
    Route::put('updatefactory/{id}', [FactoryController::class, 'update']);
    Route::delete('deletefactory/{id}', [FactoryController::class, 'destroy']);

});
// user routes
Route::prefix('users')->group(function () {

    Route::get('all', [UserController::class, 'index']);        // Show all users
    Route::post('add', [UserController::class, 'store']);       // Add user
    Route::get('edit/{id}', [UserController::class, 'edit']);   // Get single user
    Route::put('update/{id}', [UserController::class, 'update']);// Update user
    Route::delete('delete/{id}', [UserController::class, 'destroy']); // Delete user

});

Route::prefix('machines')->group(function () {

    Route::get('/all', [MachineController::class, 'index']);        
    Route::post('/add_machine', [MachineController::class, 'store']);       
    Route::get('edit_machine/{id}', [MachineController::class, 'edit']);     
    Route::put('update_machine/{id}', [MachineController::class, 'update']);   
    Route::delete('delete_machine/{id}', [MachineController::class, 'destroy']); 
});



Route::prefix('productions')->group(function () {

    Route::get('/all_production', [ProductionController::class, 'index']);        
    Route::post('/add_production', [ProductionController::class, 'store']);       
    Route::get('/edit_production/{id}', [ProductionController::class, 'edit']);   
    Route::put('/update_production/{id}', [ProductionController::class, 'update']);   
    Route::delete('/delete_production/{id}', [ProductionController::class, 'destroy']); 
});

Route::prefix('employees')->group(function () {

    Route::get('/all_employee', [EmployeeController::class, 'index']);      
    Route::post('/add_employee', [EmployeeController::class, 'store']);     
    Route::get('/edit_employee/{id}', [EmployeeController::class, 'edit']); 
    Route::put('/update_employee/{id}', [EmployeeController::class, 'update']); 
    Route::delete('/delete_employee/{id}', [EmployeeController::class, 'destroy']); 

});

Route::prefix('attendence')->group(function () {

    Route::get('/all_attendence', [AttendenceController::class, 'index']);        
    Route::post('/add_attendence', [AttendenceController::class, 'store']);       
    Route::get('/edit_attendence/{id}', [AttendenceController::class, 'edit']);   
    Route::put('/update_attendence/{id}', [AttendenceController::class, 'update']);   
    Route::delete('/delete_attendence/{id}', [AttendenceController::class, 'destroy']); 

});
// Machine details route
Route::get(
    '/machines/details/{id}',
    [MachineController::class, 'details']
);