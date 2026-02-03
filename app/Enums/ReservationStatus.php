<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case RESERVED = "reserved";

    case ISSUED = "issued";

    case COMPLETED = "completed";
}
