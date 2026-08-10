<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'NotificationCollection',
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
        ),
        new OA\Property(
            property: 'notifiableId',
            type: 'integer',
        ),
        new OA\Property(
            property: 'notificationData',
            type: 'string',
        ),
        new OA\Property(
            property: 'readAt',
            type: 'integer',
        ),
        new OA\Property(
            property: 'createdAt',
            type: 'integer',
        ),
        new OA\Property(
            property: 'updatedAt',
            type: 'integer',
        ),
    ],
    type: 'object'
)]
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
