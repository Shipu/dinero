<?php

namespace App\Models;

use Bavix\Wallet\Models\Wallet as BaseWallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends BaseWallet
{
    use HasFactory;

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
