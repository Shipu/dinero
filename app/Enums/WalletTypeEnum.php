<?php

namespace App\Enums;

enum WalletTypeEnum: string
{
    case GENERAL = 'general';
    case CREDIT_CARD = 'credit_card';
}