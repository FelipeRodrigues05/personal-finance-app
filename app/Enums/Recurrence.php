<?php declare(strict_types = 1);

namespace App\Enums;

enum Recurrence: string
{
    case DAILY   = 'daily';
    case MONTHLY = 'monthly';
}
