<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'UserPublicCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            description: 'Список пользователей',
            items: new OA\Items(
                ref: '#/components/schemas/UserPublicResource'
            )
        ),
    ],
    type: 'object'
)]
class UserPublicCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                ];
            })
        ];
    }
}
