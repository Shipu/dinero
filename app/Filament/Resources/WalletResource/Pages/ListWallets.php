<?php

namespace App\Filament\Resources\WalletResource\Pages;

use App\Enums\WalletTypeEnum;
use App\Filament\Resources\WalletResource;
use App\Models\Wallet;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListWallets extends ListRecords
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->icon('lucide-wallet')
                ->badge(Wallet::tenant()->count()),
            WalletTypeEnum::GENERAL->value => Tab::make()
                ->icon('badge-dollar-sign')
                ->badge(Wallet::tenant()->where('type', WalletTypeEnum::GENERAL->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->tenant()->where('type', WalletTypeEnum::GENERAL->value)),
            WalletTypeEnum::CREDIT_CARD->value => Tab::make()
                ->icon('lucide-credit-card')
                ->badge(Wallet::tenant()->where('type', WalletTypeEnum::CREDIT_CARD->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->tenant()->where('type', WalletTypeEnum::CREDIT_CARD->value)),
        ];
    }
}
