<?php

namespace App\Enums;

enum BloodInventoryStatus: int
{
    case InStock = 0;
    case Used = 1;
    case Expired = 2;
    case Discarded = 3;
}
