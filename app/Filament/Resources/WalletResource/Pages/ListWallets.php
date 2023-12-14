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
            Actions\CreateAction::make()->slideOver(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->icon('lucide-wallet')
                ->badge(Wallet::tenant()->count()),
            WalletTypeEnum::MOBILE_BANKING_ACCOUNT->value => Tab::make()
                ->icon('lucide-phone')
                ->badge(Wallet::tenant()->where('type', WalletTypeEnum::MOBILE_BANKING_ACCOUNT->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->tenant()->where('type', WalletTypeEnum::MOBILE_BANKING_ACCOUNT->value)),
            WalletTypeEnum::DIGITAL_WALLET->value => Tab::make()
                ->icon('lucide-wallet')
                ->badge(Wallet::tenant()->where('type', WalletTypeEnum::DIGITAL_WALLET->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->tenant()->where('type', WalletTypeEnum::DIGITAL_WALLET->value)),
            WalletTypeEnum::FREELANCER_ACCOUNT->value => Tab::make()
                ->icon('lucide-briefcase')
                ->badge(Wallet::tenant()->where('type', WalletTypeEnum::FREELANCER_ACCOUNT->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->tenant()->where('type', WalletTypeEnum::FREELANCER_ACCOUNT->value)),
            WalletTypeEnum::SAVING_ACCOUNT->value => Tab::make()
                ->icon('lucide-piggy-bank')
                ->badge(Wallet::tenant()->where('type', WalletTypeEnum::SAVING_ACCOUNT->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->tenant()->where('type', WalletTypeEnum::SAVING_ACCOUNT->value)),
            WalletTypeEnum::MUDARABA_SCHEME_ACCOUNT->value => Tab::make()
                ->icon('lucide-home')
                ->badge(Wallet::tenant()->where('type', WalletTypeEnum::MUDARABA_SCHEME_ACCOUNT->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->tenant()->where('type', WalletTypeEnum::MUDARABA_SCHEME_ACCOUNT->value)),
        ];
    }
}
