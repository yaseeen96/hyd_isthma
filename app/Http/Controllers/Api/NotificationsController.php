<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ListNotificationsResource;
use App\Models\Notification;
use Google\Rpc\Context\AttributeContext\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Type\Integer;

class NotificationsController extends Controller
{
    public function listNotifications()
    {
        $notifications = Notification::get();
        $filteredNotifications = ListNotificationsResource::collection($notifications)
        ->filter(function ($notification) {
            return !empty($notification->toArray(request()));
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => $filteredNotifications,
        ], Response::HTTP_OK);
    }
    public function getNotification($id)
    {
        if(empty($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification id is required',
            ], Response::HTTP_BAD_REQUEST);
        }
        $notification = Notification::find($id);
        if(empty($notification)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found',
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => 'success',
            'data' => new ListNotificationsResource($notification),
        ], Response::HTTP_OK);
    }
}