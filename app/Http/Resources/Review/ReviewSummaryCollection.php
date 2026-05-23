<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'ReviewSummaryCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            description: 'Список отзывов',
            items: new OA\Items(
                ref: '#/components/schemas/ReviewSummaryResource'
            )
        ),
    ],
    type: 'object'
)]

class ReviewSummaryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = ReviewSummaryResource::class;

}
