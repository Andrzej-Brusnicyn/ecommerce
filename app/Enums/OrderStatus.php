<?php

namespace App\Enums;

enum OrderStatus: string
{
    case New = 'new';
    case InProcess = 'in process';
    case Shipping = 'shipping';
    case Delivering = 'delivering';
    case Cancelled = 'cancelled';
}
