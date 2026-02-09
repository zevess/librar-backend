<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case RESERVED = "reserved";

    case CANCELED = "canceled";

    case ISSUED = "issued";

    case COMPLETED = "completed";
}
