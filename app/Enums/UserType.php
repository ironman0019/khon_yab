<?php

namespace App\Enums;

enum UserType: int
{
    case Receiver = 0;
    case Donor = 1;
    case Laboratory = 2;
}
