<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($notification) {
                return [
                    'id' => $notification->id,
                    'notifiableId' => $notification->notifiable_id,
                    'notificationData' => $notification->data,
                    'readAt' => $notification->read_at,
                    'createdAt' => $notification->created_at,
                    'updatedAt' => $notification->updated_at
                ];
            })
        ];
    }
}
