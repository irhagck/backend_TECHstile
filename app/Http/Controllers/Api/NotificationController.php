<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    // get user notifications
    public function index(Request $request, $user = null)
    {
        $userId = $user ?? optional($request->user())->id;

        if (!$userId) {
            return response()->json([
                'success' => true,
                'notifications' => []
            ]);
        }

        $notifications = Notification::with([
            'sender',
            'production.machineemploye',
            'production.employeedetails.user'
        ])
        ->where('user_id', $userId)
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
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
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

    // unread count
    public function unreadCount($userId)
    {
        $count = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}