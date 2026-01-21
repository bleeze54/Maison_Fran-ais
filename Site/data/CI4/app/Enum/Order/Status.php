<?php

namespace App\Enum\Order;

enum Status: string
{
    case EN_ATTENTE = 'en_attente';
    case LIVREE     = 'livree';
    case ANNULEE    = 'annulee';
    case REMBOURSEE = 'remboursee';
}
