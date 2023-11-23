<?php

namespace App\Models;

use App\Models\Account;
use App\Enums\WalletTypeEnum;
use Filament\Facades\Filament;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Shipu\Watchable\Traits\WatchableTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Bavix\Wallet\Models\Wallet as BaseWallet;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Wallet extends BaseWallet implements HasMedia
{
    use HasFactory, WatchableTrait, SoftDeletes, InteractsWithMedia;

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
        'start_date',
        'return_period_of_month',
        'aprox_roi',
        'statement_day_of_month',
        'payment_due_day_of_month',
        'credit_limit',
        'decimal_places',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'balance' => 'decimal:2',
        'aprox_roi' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'decimal_places' => 'integer',
        'type' => WalletTypeEnum::class,
    ];

    public function scopeTenant(Builder $query): Builder
    {
        return $query->where('account_id', optional(Filament::getTenant())->id);
    }

    public function scopeMTDR($scope){
        return $scope->where('type', WalletTypeEnum::MUDARABA_SCHEME_ACCOUNT);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function measures(): HasMany
    {
        return $this->hasMany(Mature::class);
    }

    public function TotalMatureAmount(): float
    {
        return $this->measures()->sum('amount');
    }

    public function TotalMaturePaidAmount(): float
    {
        return $this->measures()->where('is_paid', true)->sum('amount');
    }

    public function TotalMatureUnpaidAmount(): float
    {
        return $this->measures()->where('is_paid', false)->sum('amount');
    }

    public function TotalMaturePaidPercentage(): float
    {
        return $this->TotalMaturePaidAmount() / $this->TotalMatureAmount() * 100;
    }

    public function AproxRoiAmountPerMature(): float
    {
        return $this->balance * (
            (($this->aprox_roi / 12) * 3) / 100
        );
    }

    public function onModelSaving(): void
    {
        if(filled(auth()->user())) {
            $this->holder_type = User::class;
            $this->holder_id = auth()->user()->id;
        }
        $this->meta = array_merge($this->meta ?? [], [
            'currency' => $this->currency_code,
        ]);
    }

    public function onModelCreated(): void
    {
        $amount = $this->meta['initial_balance'] ?? 0;
        if($this->type == WalletTypeEnum::CREDIT_CARD->value) {
            $amount = $this->meta['total_due'] ?? 0;
            if($amount > 0) {
                $this->withdraw($amount, ['description' => 'Initial credit card due']);
            }
        } elseif($amount > 0) {
            $this->deposit($amount, ['description' => 'Initial balance']);
        }
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}
