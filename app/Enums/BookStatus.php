<?php

namespace App\Enums;

enum BookStatus: string
{
    case ALL = "all";

    case AVAILABLE = "available";

    case RESERVED = "reserved";

}
