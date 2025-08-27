<?php declare(strict_types = 1);

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case CREDIT = 'Credit'; // OUTPUT
    case DEBIT  = 'Debit';   // INPUT
}
