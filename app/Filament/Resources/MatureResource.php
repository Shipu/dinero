<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Mature;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MatureResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MatureResource\RelationManagers;

class MatureResource extends Resource
{
    protected static ?string $model = Mature::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Forms\Components\Select::make('wallet_id')
                        ->relationship('matureWallet', 'name')
                        ->required(),
                    Forms\Components\DatePicker::make('mature_date')
                        ->required(),
                    Forms\Components\TextInput::make('expected_amount')
                        ->required()
                        ->numeric()
                        ->default(0),
                ])
                ->columns(3),

                Section::make([
                    Forms\Components\Toggle::make('is_paid')
                        ->inline(false)
                        ->required(),
                    Forms\Components\TextInput::make('actual_amount')
                        ->required()
                        ->numeric()
                        ->default(0),
                ])
                ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wallet.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mature_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('actual_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMatures::route('/'),
            'create' => Pages\CreateMature::route('/create'),
            'edit' => Pages\EditMature::route('/{record}/edit'),
        ];
    }
}
