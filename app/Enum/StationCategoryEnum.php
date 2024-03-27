<?php

namespace App\Enum;

use App\Traits\EnumHelper;

enum StationCategoryEnum: string
{
    use EnumHelper;

    case QUARY = 'Quary';
    case DISPOSAL = 'Disposal';
    case GAS = 'Gas';
}
