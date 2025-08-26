<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case CREDIT = "credit"; // OUTPUT
    case DEBIT = "debit";   // INPUT
}
