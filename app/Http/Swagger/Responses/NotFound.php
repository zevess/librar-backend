<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'NotFound',
    description: 'Не найдено'
)]
class NotFound
{
}