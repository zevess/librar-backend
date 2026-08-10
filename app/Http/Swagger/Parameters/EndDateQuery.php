<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'EndDateQuery',
    name: 'end_date',
    in: 'query',
    description: 'Дата окончания (в формате ГГГГ-мм-дд)',
    schema: new OA\Schema(type: 'string')
)]
class EndDateQuery
{
}