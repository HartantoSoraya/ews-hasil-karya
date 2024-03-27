<?php

namespace App\Enum;

use App\Traits\EnumHelper;

enum FuelTypeEnum: string
{
    use EnumHelper;

    case DIESEL = 'diesel';
}
