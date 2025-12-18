<?php

namespace App\Enums;

enum UserType: int
{
    case User = 0;
    case Donor = 1;
    case HospitalUser = 2;
}
