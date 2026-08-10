<?php

namespace App\Http\Controllers;

use App\Http\Resources\Notification\NotificationCollection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function readNotifications()
    {
        $notifications = DatabaseNotification::where('notifiable_id', Auth::id())->get();
        if ($notifications) {
            $notifications->markAsRead();
        }
        return true;
    }

    public function showByUser(): NotificationCollection
    {
        $notifications = DatabaseNotification::where('notifiable_id', Auth::id())->orderByDesc('created_at')->get();
        return new NotificationCollection($notifications);
    }
}
