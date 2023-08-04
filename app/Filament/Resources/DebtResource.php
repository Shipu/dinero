<?php

namespace App\Filament\Resources;

use App\Enums\DebtTypeEnum;
use App\Filament\Resources\DebtResource\Pages;
use App\Filament\Resources\DebtResource\RelationManagers;
use App\Models\Debt;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DebtResource extends Resource
{
    protected static ?string $model = Debt::class;

    protected static ?string $navigationIcon = 'helping-hand';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Radio::make('type')
                            ->label(__('debts.fields.type'))
                            ->options(__('debts.types'))
                            ->inline()
                            ->default(DebtTypeEnum::PAYABLE->value)
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label(__('debts.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('amount')
                            ->label(__('debts.fields.amount'))
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Select::make('wallet_id')
                            ->label(__('debts.fields.wallet'))
                            ->relationship('wallet', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DateTimePicker::make('start_at')
                            ->label(__('debts.fields.start_at'))
                            ->default(now()),
                        Forms\Components\ColorPicker::make('color')
                            ->label(__('debts.fields.color')),
                        Forms\Components\Textarea::make('description')
                            ->label(__('debts.fields.description'))
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('debts.fields.color')),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(function (string $state) {
                        return match ($state) {
                            DebtTypeEnum::PAYABLE->value => 'danger',
                            DebtTypeEnum::RECEIVABLE->value => 'success',
                        };
                    })
                    ->formatStateUsing(fn(string $state) => __('debts.types.' . $state))
                    ->label(__('debts.fields.type'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('debts.fields.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('debts.fields.amount'))
                    ->numeric()
                    ->sortable(),
                BadgeableColumn::make('balance')
                    ->label(__('goals.fields.balance'))
                    ->suffixBadges([
                        Badge::make('progress')
                            ->label(fn(Model $record) => $record->progress. '%')
                    ]),
                Tables\Columns\TextColumn::make('wallet.name')
                    ->label(__('debts.fields.wallet'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->label(__('debts.fields.start_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(20)
                    ->searchable()
                    ->label(__('debts.fields.description')),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListDebts::route('/'),
            'create' => Pages\CreateDebt::route('/create'),
            'edit' => Pages\EditDebt::route('/{record}/edit'),
        ];
    }    
}
