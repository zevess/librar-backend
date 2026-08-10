<?php

namespace App\Http\Resources\Genre;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'GenreResource',
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
            property: 'slug',
            type: 'string',
        ),
        new OA\Property(
            property: 'isDeleted',
            type: 'boolean',
        ),
    ],
    type: 'object'
)]
class GenreResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'isDeleted' => (bool) $this->deleted_at,
        ];
    }
}
