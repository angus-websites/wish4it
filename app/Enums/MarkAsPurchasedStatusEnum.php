<?php

namespace App\Enums;

enum MarkAsPurchasedStatusEnum: string
{

    case SUCCESS = 'success';
    case ALREADY_PURCHASED = 'already_purchased';
    case HAS_CHANGED = "has_changed";
    case FAILED = "failed";

}
