<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Filament\Resources\TransactionResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return (new TransactionResource())->form($form);
    }

    public function table(Table $table): Table
    {
        return (new TransactionResource())->table($table);
    }
}
