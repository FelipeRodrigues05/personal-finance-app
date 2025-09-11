<?php declare(strict_types = 1);

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case EXPENSE = 'expense';   // OUTPUT
    case INCOME  = 'income';        // INPUT
}
