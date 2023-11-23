<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Shipu\Watchable\Traits\WatchableTrait;

class Mature extends Model
{
    use HasFactory, WatchableTrait;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'mature_date',
        'expected_amount',
        'is_paid',
        'actual_amount',
        'account_id',
    ];

    protected $casts = [
        'mature_date' => 'date',
        'expected_amount' => 'decimal:2',
        'is_paid' => 'boolean',
        'actual_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function matureWallet(): BelongsTo
    {
        return $this->wallet()->mtdr();
    }

    public function scopeUpcoming($scope){
        return $scope->where('mature_date', '>=', now()->format('Y-m-d'));
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function scopeTenant(Builder $query): Builder
    {
        return $query->where('account_id', optional(Filament::getTenant())->id);
    }

    public function onModelSaving(): void
    {
        if(filled(auth()->user())) {
            $this->user_id = optional(Filament::getTenant())->owner_id;
        }
    }



}
