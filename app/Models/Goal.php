<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shipu\Watchable\Traits\WatchableTrait;

class Goal extends Model
{
    use HasFactory, WatchableTrait;

    protected $fillable = [
        'name',
        'amount',
        'target_date',
        'color',
        'currency_code',
    ];

    public function progress(): Attribute
    {
        return Attribute::make(
            get: function() {
                return $this->amount > 0 ? ($this->balance / $this->amount) * 100 : 0;
            }
        );
    }

    public function balance(): Attribute
    {
        return Attribute::make(
            get: function() {
                return $this->transactions->sum('amount') * -1;
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

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'reference');
    }
}
