<?php

namespace App\Enums;

enum PromiseItemStatusEnum: string
{
    case PENDING = 'pending';
    case PROMISED = 'promised';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';
}
