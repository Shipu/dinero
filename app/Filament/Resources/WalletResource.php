<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Goal;
use Filament\Tables;
use App\Models\Wallet;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\WalletTypeEnum;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\ColorPicker;
use Guava\FilamentIconPicker\Forms\IconPicker;
use App\Filament\Resources\WalletResource\Pages;
use App\Filament\Resources\WalletResource\RelationManagers;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

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
                            ->autofocus()
                            ->columnSpan([
                                'sm' => 1,
                            ]),
                        Select::make('type')
                            ->label(__('wallets.fields.type'))
                            ->columnSpan([
                                'sm' => 1,
                            ])
                            ->searchable()
                            ->required()
                            ->options(__('wallets.types'))
                            ->default(WalletTypeEnum::GENERAL->value)
                            ->live()
                            // ->disabled(fn (string $operation): bool => $operation !== 'create')
                            ,
                        TextInput::make('balance')
                            ->label(fn(string $operation): string => $operation == 'create' ? __('wallets.fields.initial_balance') : __('wallets.fields.balance'))
                            ->required()
                            ->numeric()
                            ->inputMode('decimal')
                            ->default(0)
                            ->disabled()
                            ->visible(fn (Get $get, string $operation): bool => $get('type') != WalletTypeEnum::CREDIT_CARD->value && $operation !== 'create'),
                        TextInput::make('meta.initial_balance')
                            ->label(__('wallets.fields.initial_balance'))
                            ->required()
                            ->numeric()
                            ->columnSpan([
                                'sm' => 2,
                            ])
                            ->inputMode('decimal')
                            ->default(0)
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
                            ->columnSpan([
                                'sm' => 1,
                            ])
                            ->options(country_with_currency_and_symbol())
                            ->default('BDT'),
                        ColorPicker::make('color')
                            ->label(__('wallets.fields.color'))
                            ->required()
                            ->columnSpan([
                                'sm' => 1,
                            ])
                            ->default('#22b3e0'),
                        IconPicker::make('icon')
                            ->label(__('wallets.fields.icon'))
                            ->columnSpan([
                                'sm' => 2,
                            ])
                            ->columns([
                                'default' => 1,
                                'lg' => 3,
                                '2xl' => 5,
                            ]),
                        Select::make('statement_day_of_month')
                            ->label(__('wallets.fields.statement_day_of_month'))
                            ->options(month_ordinal_numbers())
                            ->required()
                            ->visible(fn (Get $get): bool => $get('type') === WalletTypeEnum::CREDIT_CARD->value),
                        Select::make('payment_due_day_of_month')
                            ->label(__('wallets.fields.payment_due_day_of_month'))
                            ->options(month_ordinal_numbers())
                            ->required()
                            ->visible(fn (Get $get): bool => $get('type') === WalletTypeEnum::CREDIT_CARD->value),
                        Forms\Components\Toggle::make('exclude')
                            ->label(__('wallets.fields.exclude.title'))
                            ->helperText(__('wallets.fields.exclude.help_text'))
                            ->default(false)
                            ->visible(fn (Get $get): bool => $get('type') !== WalletTypeEnum::CREDIT_CARD->value),
                        SpatieMediaLibraryFileUpload::make('wallet_document')
                            ->label(__('wallets.fields.wallet_document'))
                            ->maxFiles(5)
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->columnSpan([
                                'sm' => 2,
                            ])
                    ])->columns(),
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
                            WalletTypeEnum::BANK_ACCOUNT->value => 'primary',
                            WalletTypeEnum::MOBILE_BANKING_ACCOUNT->value => 'primary',
                            WalletTypeEnum::FREELANCER_ACCOUNT->value => 'primary',
                            WalletTypeEnum::SAVING_ACCOUNT->value => 'primary',
                            WalletTypeEnum::MUDARABA_SCHEME_ACCOUNT->value => 'primary',
                            WalletTypeEnum::CASH->value => 'primary',
                            WalletTypeEnum::INVESTMENT->value => 'primary',
                            WalletTypeEnum::LOAN->value => 'primary',
                            WalletTypeEnum::OTHER->value => 'primary',
                            default => 'primary',
                        };
                    })
                    ->formatStateUsing(fn (string $state): string => __("wallets.types.{$state}"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency_code')
                    ->label(__('wallets.fields.currency_code'))
                    ->formatStateUsing(fn (string $state): string => country_with_currency_and_symbol($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->label(__('wallets.fields.balance'))
                    ->alignRight()
                    ->formatStateUsing(fn (float $state, Model $record): string => curency_money_format($state, $record?->currency_code))
                    ->weight('bold')
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
                Action::make('refresh_balance')
                    ->label(__('wallets.actions.refresh_balance'))
                    ->icon('lucide-refresh-cw')
                    ->color('warning')
                    ->action(function (Wallet $wallet) {
                        $wallet->refreshBalance();
                        Notification::make()
                            ->title("{$wallet->name} Wallet")
                            ->body(__('wallets.notifications.balance_refreshed'))
                            ->icon('lucide-refresh-cw')
                            ->color('success')
                            ->send();
                    }),
                Tables\Actions\EditAction::make()->slideOver(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->emptyStateActions([
                Tables\Actions\CreateAction::make()->slideOver(),
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
//            'create' => Pages\CreateWallet::route('/create'),
//            'edit' => Pages\EditWallet::route('/{record}/edit'),
        ];
    }
}
