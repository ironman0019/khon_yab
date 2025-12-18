<?php

namespace App\Enums;

enum BloodRequestStatus: int
{
    case Pending = 0;
    case Approved = 1;
    case Rejected = 2;
    case Completed = 3;
}
