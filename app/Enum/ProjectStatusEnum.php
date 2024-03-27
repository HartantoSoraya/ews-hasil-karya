<?php

namespace App\Enum;

use App\Traits\EnumHelper;

enum ProjectStatusEnum: string
{
    use EnumHelper;

    case ON_GOING = 'On Going';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled';
    case ON_HOLD = 'On Hold';
    case PENDING = 'Pending';
    case DRAFT = 'Draft';
    case ARCHIVED = 'Archived';
    case DELETED = 'Deleted';
}
