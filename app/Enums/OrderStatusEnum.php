<?php

namespace App\Enums;


enum OrderStatusEnum: int
{
    case PENDING = 0;
    case COMPLETED = 1;
    case REJECTED = 2;
}
