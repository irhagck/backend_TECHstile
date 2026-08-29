<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    // get user notifications
    public function index(Request $request)
{
    $user = $request->user();

    $notifications = Notification::with([
        'sender',
        'user.role',
        'production'
    ])
    ->whereHas('user.role', function ($query) use ($user) {
        $query->where('id', $user->role_id);
    })
    ->where('user_id', $user->id)
    ->latest()
    ->get();

    return response()->json([
        'success' => true,
        'notifications' => $notifications
    ]);
}



// mark read

public function read(Request $request, $id)
{
    $user = $request->user();

    $notification = Notification::where('id', $id)
        ->where('user_id', $user->id)
        ->first();

    if (!$notification) {
        return response()->json([
            'message' => 'Not found'
        ], 404);
    }

    $notification->update([
        'is_read' => true
    ]);

    return response()->json([
        'success' => true
    ]);
}





// create notification

public function store(Request $request)
{


$notification =
Notification::create([
    'user_id'        => $employeeUserId,
    'production_id'  => $prod->id,
    'sender_id'      => $request->user()->id,

    'title'          => $request->action === 'approve'
        ? 'Production Approved'
        : 'Production Rejected',

    'message'        => $request->action === 'approve'
        ? "Your production #{$prod->id} has been approved. Machine ID: {$prod->machine_id}, Ready Quantity: {$prod->ready_quantity}"
        : "Your production #{$prod->id} has been rejected",

    'type'           => $request->action === 'approve'
        ? 'approved'
        : 'rejected',
]);


return response()->json($notification);

}
public function unreadCount($userId)
{

$count = Notification::where('user_id',$userId)
->where('is_read',false)
->count();


return response()->json([
    'count'=>$count
]);

}


}