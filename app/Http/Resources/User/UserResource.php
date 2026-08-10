<?php

namespace App\Http\Resources\User;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserResource',
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
        ),
        new OA\Property(
            property: 'name',
            type: 'string',
        ),
        new OA\Property(
            property: 'email',
            type: 'string',
        ),
        new OA\Property(
            property: 'role',
            type: 'string',
        ),
        new OA\Property(
            property: 'notifications',
            type: 'integer',
        ),
        new OA\Property(
            property: 'isVerified',
            type: 'boolean',
        ),
        new OA\Property(
            property: 'isDeleted',
            type: 'boolean',
        ),
    ],
    type: 'object'
)]
class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isSameUser = $request->user() && $request->user()->is($this->resource);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'notifications' => $this->when($isSameUser, $this->unreadNotifications()->count()),
            'isVerified' => $this->hasVerifiedEmail(),
            'isDeleted' => (bool) $this->deleted_at
        ];
    }
}
