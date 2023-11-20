<?php

namespace App\Enums;

enum WalletTypeEnum: string
{
    case GENERAL = 'general';
    case CREDIT_CARD = 'credit_card';
    case BANK_ACCOUNT = 'bank_account';
    case MOBILE_BANKING_ACCOUNT = 'mobile_banking_account';
    case DIGITAL_WALLET = 'digital_wallet';
    case FREELANCER_ACCOUNT = 'freelancer_account';
    case SAVING_ACCOUNT = 'saving_account';
    case MUDARABA_SCHEME_ACCOUNT = 'mudaraba_scheme_account';
    case CASH = 'cash';
    case INVESTMENT = 'investment';
    case LOAN = 'loan';
    case OTHER = 'other';

}