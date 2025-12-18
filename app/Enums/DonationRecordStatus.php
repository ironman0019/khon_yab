<?php

namespace App\Enums;

enum DonationRecordStatus: int
{
    case TestPending = 0;
    case Safe = 1;
    case Unsafe = 2;
    case Discarded = 3;
}
