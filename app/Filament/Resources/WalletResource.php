<?php

namespace App\Filament\Resources;

use App\Enums\WalletTypeEnum;
use App\Filament\Resources\WalletResource\Pages;
use App\Filament\Resources\WalletResource\RelationManagers;
use App\Models\Wallet;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Illuminate\Database\Eloquent\Model;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static ?string $navigationIcon = 'lucide-wallet';

    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('wallets.fields.name'))
                            ->required()
                            ->autofocus(),
                        Select::make('type')
                            ->label(__('wallets.fields.type'))
                            ->searchable()
                            ->required()
                            ->options(__('wallets.types'))
                            ->default(WalletTypeEnum::GENERAL->value)
                            ->live()
                            ->disabled(fn (string $operation): bool => $operation !== 'create'),
                        TextInput::make('balance')
                            ->label(fn(string $operation): string => $operation == 'create' ? __('wallets.fields.initial_balance') : __('wallets.fields.balance'))
                            ->required()
                            ->numeric()
                            ->inputMode('decimal')
                            ->default(0)
                            ->columnSpan(2)
                            ->disabled()
                            ->visible(fn (Get $get, string $operation): bool => $get('type') == WalletTypeEnum::GENERAL->value && $operation !== 'create'),
                        TextInput::make('meta.initial_balance')
                            ->label(__('wallets.fields.initial_balance'))
                            ->required()
                            ->numeric()
                            ->inputMode('decimal')
                            ->default(0)
                            ->columnSpan(2)
                            ->visible(fn (Get $get, string $operation): bool => $get('type') == WalletTypeEnum::GENERAL->value && $operation == 'create'),
                        TextInput::make('meta.credit')
                            ->label(__('wallets.fields.credit_limit'))
                            ->required()
                            ->numeric()
                            ->inputMode('decimal')
                            ->default(0)
                            ->columnSpan(fn(string $operation): int => $operation == 'create' ? 1 : 2)
                            ->visible(fn (Get $get): bool => $get('type') == WalletTypeEnum::CREDIT_CARD->value),
                        TextInput::make('meta.total_due')
                            ->label(__('wallets.fields.total_due'))
                            ->required()
                            ->numeric()
                            ->inputMode('decimal')
                            ->default(0)
                            ->visible(fn (Get $get, string $operation): bool => $get('type') == WalletTypeEnum::CREDIT_CARD->value && $operation == 'create'),
                        Select::make('currency_code')
                            ->label(__('wallets.fields.currency_code'))
                            ->required()
                            ->searchable()
                            ->options(config('utilities.currencies'))
                            ->default('BDT'),
                        ColorPicker::make('color')
                            ->label(__('wallets.fields.color'))
                            ->required()
                            ->default('#22b3e0'),
                        IconPicker::make('icon')
                            ->label(__('wallets.fields.icon'))
                            ->columnSpan(2)
                            ->columns([
                                'default' => 1,
                                'lg' => 3,
                                '2xl' => 5,
                            ]),
                        Select::make('statement_day_of_month')
                            ->label(__('wallets.fields.statement_day_of_month'))
                            ->options(config('utilities.month_ordinal_numbers'))
                            ->required()
                            ->visible(fn (Get $get): bool => $get('type') === WalletTypeEnum::CREDIT_CARD->value),
                        Select::make('payment_due_day_of_month')
                            ->label(__('wallets.fields.payment_due_day_of_month'))
                            ->options(config('utilities.month_ordinal_numbers'))
                            ->required()
                            ->visible(fn (Get $get): bool => $get('type') === WalletTypeEnum::CREDIT_CARD->value),
                        Forms\Components\Toggle::make('exclude')
                            ->label(__('wallets.fields.exclude.title'))
                            ->helperText(__('wallets.fields.exclude.help_text'))
                            ->default(false)
                            ->visible(fn (Get $get): bool => $get('type') === WalletTypeEnum::GENERAL->value),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('wallets.fields.name'))
                    ->color(fn (?Model $record) => Color::hex($record->color))
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('wallets.fields.type'))
                    ->badge()
                    ->color(function (string $state) {
                        return match ($state) {
                            WalletTypeEnum::CREDIT_CARD->value => 'danger',
                            WalletTypeEnum::GENERAL->value => 'success',
                        };
                    })
                    ->formatStateUsing(fn (string $state): string => __("wallets.types.{$state}"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->label(__('wallets.fields.balance'))
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency_code')
                    ->label(__('wallets.fields.currency_code'))
                    ->formatStateUsing(fn (string $state): string => config("utilities.currencies.{$state}"))
                    ->sortable(),

            ])
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('wallets.fields.type'))
                    ->options(__('wallets.types'))
                    ->multiple()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\TransactionsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWallets::route('/'),
            'create' => Pages\CreateWallet::route('/create'),
            'edit' => Pages\EditWallet::route('/{record}/edit'),
        ];
    }
}
