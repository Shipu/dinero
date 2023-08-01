<?php

namespace App\Models;

use App\Enums\WalletTypeEnum;
use Bavix\Wallet\Models\Wallet as BaseWallet;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeTenant(Builder $query): Builder
    {
        return $query->where('account_id', optional(Filament::getTenant())->id);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function onModelSaving(): void
    {
        $this->holder_type = User::class;
        $this->holder_id = auth()->user()->id;
        $this->meta = [
            'currency' => $this->currency_code,
        ];
    }

    public function onModelCreated(): void
    {
        $amount = $this->meta['initial_balance'] ?? 0;
        dd($amount, $this->meta);
        if($this->type == WalletTypeEnum::CREDIT_CARD->value) {
            $amount = $this->meta['total_due'] ?? 0;
            if($amount > 0) {
                $this->withdraw($amount, ['description' => 'Initial credit card due']);
            }
        } elseif($amount > 0) {
            $this->deposit($amount, ['description' => 'Initial balance']);
        }
    }
}
