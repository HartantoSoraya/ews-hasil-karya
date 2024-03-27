<?php

namespace App\Enum;

use App\Traits\EnumHelper;

enum AggregateFunctionEnum: string
{
    use EnumHelper;

    case MIN = 'min';
    case MAX = 'max';
    case AVG = 'avg';
    case SUM = 'sum';
    case COUNT = 'count';
}
