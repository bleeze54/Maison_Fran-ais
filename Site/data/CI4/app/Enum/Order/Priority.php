<?php

namespace App\Enum\Order;

enum Priority: string
{
    case STANDARD     = 'standard';
    case PRIORITAIRE  = 'prioritaire';
    case RELAIS       = 'point_relais';
}
