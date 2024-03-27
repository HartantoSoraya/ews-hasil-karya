<?php

namespace App\Enum;

use App\Traits\EnumHelper;

enum UserRoleEnum: string
{
    use EnumHelper;

    case DEV = 'dev';
    case CLIENT = 'client';
    case CLIENT_USER = 'client_user';
}
