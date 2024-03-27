<?php

namespace App\Enum;

use App\Traits\EnumHelper;

enum DatePeriodEnum: string
{
    use EnumHelper;

    case TODAY = 'today';
    case WEEK = 'week';
    case MONTH = 'month';
    case YEAR = 'year';
    case ALL = 'all';
}
