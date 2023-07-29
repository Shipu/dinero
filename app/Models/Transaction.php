<?php

namespace App\Models;

use App\Enums\SpendTypeEnum;
use App\Enums\TransactionTypeEnum;
use Bavix\Wallet\Internal\Service\UuidFactoryServiceInterface;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shipu\Watchable\Traits\WatchableTrait;

class Transaction extends \Bavix\Wallet\Models\Transaction
{
    use HasFactory, SoftDeletes, WatchableTrait;

    protected $fillable = [
        'payable_type',
        'payable_id',
        'wallet_id',
        'uuid',
        'type',
        'amount',
        'confirmed',
        'meta',
        'category_id',
        'account_id',
        'happened_at',
        'created_at',
        'updated_at',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function wallet(): BelongsTo
    {
        $filamentTenantId = optional(Filament::getTenant())->id;
        $relationShip = $this->belongsTo(Wallet::class);

        if(!blank($filamentTenantId)) {
            return $relationShip->where('account_id', $filamentTenantId);
        }

        return $relationShip;
    }

    public function onModelCreating(): void
    {
        if($this->from_hub) {
            unset($this->from_hub);
            $this->payable_type = User::class;
            $this->payable_id = auth()->user()->id;
            $this->uuid = app(UuidFactoryServiceInterface::class)->uuid4();
        }

        $this->type = match (optional($this->category)->type) {
            SpendTypeEnum::EXPENSE->value => TransactionTypeEnum::WITHDRAW->value,
            SpendTypeEnum::INCOME->value => TransactionTypeEnum::DEPOSIT->value,
        };
    }

    public function onModelSaving(): void
    {
        $this->meta = array_merge($this->getOriginal('meta'), $this->meta);
    }

    public function isTransferTransaction(): Attribute
    {
        return Attribute::make(
            get: function() {
                return array_get($this->meta, 'transfer', false) ?? false;
            }
        );
    }

    public function onModelSaved(): void
    {
        $this->wallet->refreshBalance();
    }
}
