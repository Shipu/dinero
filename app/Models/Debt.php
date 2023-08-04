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
        $meta = ['reference_type' => Debt::class, 'reference_id' => $this->id];
        $method = match ($this->type) {
            DebtTypeEnum::RECEIVABLE->value => 'withdraw',
            DebtTypeEnum::PAYABLE->value => 'deposit',
            default => null,
        };

        if(!blank($method)) {
            $this->wallet->{$method}($this->amount, $meta);
        }
    }

    public function onModelUpdating(): void
    {
        $meta = ['reference_type' => Debt::class, 'reference_id' => $this->id];
        $delta = $this->amount - $this->getOriginal('amount');
        $method = null;

        if($delta > 0) {
            $method = $this->type == DebtTypeEnum::RECEIVABLE->value ? 'withdraw' : 'deposit';
        } elseif ($delta != 0) {
            $delta = abs($delta);
            $method = $this->type == DebtTypeEnum::RECEIVABLE->value ? 'deposit' : 'withdraw';
        }

        if(!blank($method)) {
            $this->wallet->{$method}($delta, $meta);
        }
    }
}
