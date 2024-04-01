<?php

namespace App\Enum;

use App\Traits\EnumHelper;

enum UserRoleEnum: string
{
    use EnumHelper;

    case DEV = 'dev';
    case CLIENT = 'client';
}
