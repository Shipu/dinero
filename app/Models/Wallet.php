<?php

namespace App\Models;

use Bavix\Wallet\Models\Wallet as BaseWallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shipu\Watchable\Traits\WatchableTrait;

class Wallet extends BaseWallet
{
    use HasFactory, WatchableTrait;

    protected $fillable = [
        'holder_type',
        'holder_id',
        'name',
        'slug',
        'uuid',
        'description',
        'meta',
        'balance',
        'account_id',
        'type',
        'currency_code',
        'icon',
        'color',
        'exclude',
        'statement_day_of_month',
        'payment_due_day_of_month',
        'credit_limit',
        'decimal_places',
        'created_at',
        'updated_at',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function onModelSaving(): void
    {
        $this->holder_type = User::class;
        $this->holder_id = auth()->user()->id;
    }
}
