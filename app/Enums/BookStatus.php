<?php

namespace App\Enums;

enum BookStatus: string
{
    case AVAILABLE = "available";

    case RESERVED = "reserved";

    case ISSUED = "issued";
}
