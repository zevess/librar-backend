<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'SearchQuery',
    name: 'q',
    in: 'query',
    description: 'Поиск ресурса',
    schema: new OA\Schema(type: 'string')
)]
class SearchQuery
{
}