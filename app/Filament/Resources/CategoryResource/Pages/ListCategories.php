<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Enums\SpendTypeEnum;
use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

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
                ->icon('lucide-layout-list')
                ->iconColor('black')
                ->badge(Category::query()->count()),
            SpendTypeEnum::EXPENSE->value => Tab::make()
                ->icon('lucide-trending-down')
                ->iconColor('black')
                ->badge(Category::query()->where('type', SpendTypeEnum::EXPENSE->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', SpendTypeEnum::EXPENSE->value)),
            SpendTypeEnum::INCOME->value => Tab::make()
                ->icon('lucide-trending-up')
                ->iconColor('black')
                ->badge(Category::query()->where('type', SpendTypeEnum::INCOME->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', SpendTypeEnum::INCOME->value)),
        ];
    }
}
