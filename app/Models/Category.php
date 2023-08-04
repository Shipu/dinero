<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shipu\Watchable\Traits\WatchableTrait;

class Category extends Model
{
    use HasFactory, SoftDeletes, WatchableTrait, Sluggable;

    protected $fillable = [
        'name',
        'account_id',
        'type',
        'slug',
        'icon',
        'color',
        'status',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function balance(): Attribute
    {
        return Attribute::make(
            get: function() {
                return $this->transactions->sum('amount');
            }
        );
    }

    public function monthlyBalance(): Attribute
    {
        return Attribute::make(
            get: function() {
                return $this->transactions()->whereBetween('happened_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount');
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

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
