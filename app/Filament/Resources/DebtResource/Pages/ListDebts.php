<?php

namespace App\Filament\Resources\DebtResource\Pages;

use App\Enums\DebtTypeEnum;
use App\Filament\Resources\DebtResource;
use App\Models\Debt;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDebts extends ListRecords
{
    protected static string $resource = DebtResource::class;

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
                ->icon('helping-hand')
                ->badge(Debt::tenant()->count()),
            DebtTypeEnum::PAYABLE->value => Tab::make()
                ->icon('lucide-trending-down')
                ->badge(Debt::tenant()->where('type', DebtTypeEnum::PAYABLE->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', DebtTypeEnum::PAYABLE->value)),
            DebtTypeEnum::RECEIVABLE->value => Tab::make()
                ->icon('lucide-trending-up')
                ->badge(Debt::tenant()->where('type', DebtTypeEnum::RECEIVABLE->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', DebtTypeEnum::RECEIVABLE->value)),
        ];
    }
}
