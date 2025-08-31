<?php declare(strict_types = 1);

namespace App\Enums;

enum CardType: string
{
    case CREDIT = "Credit";
    case DEBIT  = "Debit";
}
