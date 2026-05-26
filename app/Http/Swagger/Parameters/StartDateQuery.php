<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'StartDateQuery',
    name: 'start_date',
    in: 'query',
    description: 'Дата начала (в формате ГГГГ-мм-дд)',
    schema: new OA\Schema(type: 'string')
)]
class StartDateQuery
{
}