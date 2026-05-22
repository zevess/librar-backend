<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'SlugInPath',
    name: 'slug',
    in: 'path',
    required: true,
    description: 'Slug ресурса',
    schema: new OA\Schema(type: 'string')
)]
class SlugInPath
{
}