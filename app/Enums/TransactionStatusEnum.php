<?php

namespace App\Enums;


enum TransactionStatusEnum: int
{
    case PENDING = 0;
    case SUCCESS = 1;
    case FAILED = 2;
}
