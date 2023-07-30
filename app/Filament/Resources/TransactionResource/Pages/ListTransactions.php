<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

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
                ->icon('lucide-calculator')
                ->iconColor('black')
                ->badge(Transaction::query()->count()),
            TransactionTypeEnum::WITHDRAW->value => Tab::make()
                ->icon('lucide-trending-down')
                ->iconColor('black')
                ->badge(Transaction::query()->where('type', TransactionTypeEnum::WITHDRAW->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', TransactionTypeEnum::WITHDRAW->value)),
            TransactionTypeEnum::DEPOSIT->value => Tab::make()
                ->icon('lucide-trending-up')
                ->iconColor('black')
                ->badge(Transaction::query()->where('type', TransactionTypeEnum::DEPOSIT->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', TransactionTypeEnum::DEPOSIT->value)),
        ];
    }
}
