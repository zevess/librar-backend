<?php

namespace App\Http\Controllers;

use App\Http\Resources\Notification\NotificationCollection;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{

    public function readNotifications()
    {
        $notifications = DatabaseNotification::where('notifiable_id', auth()->id())->get();
        if ($notifications) {
            $notifications->markAsRead();
        }
        return true;
    }

    public function showByUser(): NotificationCollection
    {
        $notifications = DatabaseNotification::where('notifiable_id', auth()->id())->orderByDesc('created_at')->get();
        return new NotificationCollection($notifications);
    }
}
