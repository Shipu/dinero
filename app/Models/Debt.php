<?php

namespace App\Models;

use App\Enums\DebtTypeEnum;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shipu\Watchable\Traits\WatchableTrait;

class Debt extends Model
{
    use HasFactory, SoftDeletes, WatchableTrait;

    protected $fillable = [
        'name',
        'type',
        'amount',
        'description',
        'start_at',
        'account_id',
        'wallet_id',
        'color',
    ];

    public function progress(): Attribute
    {
        return Attribute::make(
            get: function() {
                return ($this->balance / $this->amount) * 100;
            }
        );
    }

    public function balance(): Attribute
    {
        return Attribute::make(
            get: function() {
                return $this->transactions->sum('amount');
            }
        );
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function scopeTenant(Builder $query): Builder
    {
        return $query->where('account_id', optional(Filament::getTenant())->id);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'reference');
    }

    public function onModelCreated(): void
    {
        if($this->type == DebtTypeEnum::RECEIVABLE->value) {
            $this->wallet->withdraw($this->amount, ['debt' => true, 'debt_id' => $this->id]);
        } elseif ($this->type == DebtTypeEnum::PAYABLE->value) {
            $this->wallet->deposit($this->amount, ['debt' => true, 'debt_id' => $this->id]);
        }
    }

    public function onModelUpdating()
    {
        $delta = $this->amount - $this->getOriginal('amount');
        if($this->type == DebtTypeEnum::RECEIVABLE->value) {
            if($delta > 0) {
                $this->wallet->withdraw($delta, ['debt' => true, 'debt_id' => $this->id, 'update' => true]);
            } elseif ($delta != 0) {
                $this->wallet->deposit(abs($delta), ['debt' => true, 'debt_id' => $this->id, 'update' => true]);
            }
            $this->wallet->withdraw($delta, ['debt' => true, 'debt_id' => $this->id, 'update' => true]);
        } elseif ($this->type == DebtTypeEnum::PAYABLE->value) {
            if($delta > 0) {
                $this->wallet->deposit($delta, ['debt' => true, 'debt_id' => $this->id, 'update' => true]);
            } elseif ($delta != 0) {
                $this->wallet->withdraw(abs($delta), ['debt' => true, 'debt_id' => $this->id, 'update' => true]);
            }
        }
    }
}
