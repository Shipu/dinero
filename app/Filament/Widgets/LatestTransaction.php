<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestTransaction extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(TransactionResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('happened_at', 'desc')
            ->columns((new TransactionResource())->tableColumns())
            ->actions([
                Action::make('view')
                    ->url(fn (Transaction $record): string => TransactionResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
