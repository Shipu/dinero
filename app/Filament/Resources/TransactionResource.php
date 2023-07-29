<?php

namespace App\Filament\Resources;

use App\Enums\SpendTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Hidden::make('from_hub')
                            ->default(true)
                            ->visible(fn (string $operation): bool => $operation === 'create'),
                        Radio::make('type')
                            ->default(TransactionTypeEnum::WITHDRAW->value)
                            ->formatStateUsing(function (string $state, ?Model $record): string {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction) {
                                        return TransactionTypeEnum::TRANSFER->value;
                                    }
                                }
                                return $state;
                            })
                            ->disableOptionWhen(function(?Model $record){
                                if(!blank($record)) {
                                    if($record->isTransferTransaction) {
                                        return true;
                                    }
                                }
                                return false;
                            })
                            ->label(__('transactions.fields.type'))
                            ->inline()
                            ->required()
                            ->live()
                            ->columnSpan(2)
                            ->options(collect(__('transactions.types'))->pluck('label', 'id')),
                        DateTimePicker::make('happened_at')
                            ->label(__('transactions.fields.happened_at'))
                            ->native(false)
                            ->seconds(false)
                            ->displayFormat('d/m/Y h:i a')
                            ->default(now())
                            ->columnSpan(2),
                        TextInput::make('amount')
                            ->label(__('transactions.fields.amount'))
                            ->required()
                            ->disabled(function(?Model $record){
                                if(!blank($record)) {
                                    if($record->isTransferTransaction) {
                                        return true;
                                    }
                                }
                                return false;
                            })
                            ->autofocus()
                            ->columnSpan(2)
                            ->numeric(),
                        Textarea::make('description')
                            ->label(__('transactions.fields.description'))
                            ->columnSpan(2),
                        Select::make('wallet_id')
                            ->label(__('transactions.fields.wallet'))
                            ->relationship('wallet', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(function (?Model $record): int {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction) {
                                        return 2;
                                    }
                                }
                                return 1;
                            })
                            ->disabled(function (?Model $record): bool {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction) {
                                        return true;
                                    }
                                }
                                return false;
                            })
                            ->visible(function (Get $get, ?Model $record): bool {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction) {
                                        return true;
                                    }
                                }
                                return $get('type') != TransactionTypeEnum::TRANSFER->value;
                            }),
                        Select::make('category_id')
                            ->label(__('transactions.fields.category'))
                            ->relationship('category', 'name', function(Builder $query, Get $get){
                                $spendType = match ($get('type')) {
                                    TransactionTypeEnum::WITHDRAW->value => SpendTypeEnum::EXPENSE->value,
                                    TransactionTypeEnum::DEPOSIT->value => SpendTypeEnum::INCOME->value,
                                    default => null,
                                };
                                if(!is_null($spendType)) {
                                    return $query->where('type', $spendType);
                                }

                                return $query;
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(fn (Get $get): bool => $get('type') != TransactionTypeEnum::TRANSFER->value),
                        Select::make('from_wallet_id')
                            ->label( __('transactions.fields.from_wallet'))
                            ->relationship('wallet', 'name')
                            ->live()
                            ->columnSpan(function(Get $get, ?Model $record): int {
                                return blank($get('from_wallet_id')) ? 2 : 1;
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->afterStateUpdated(function (Set $set) {
                                $set('to_wallet_id', null);
                            })
                            ->visible(function(Get $get, ?Model $record): bool {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction) {
                                        return false;
                                    }
                                }
                                return $get('type') == TransactionTypeEnum::TRANSFER->value;
                            }),
                        Select::make('to_wallet_id')
                            ->label(__('transactions.fields.to_wallet'))
                            ->relationship('wallet', 'name', function(Builder $query, Get $get){
                                return $query->where('id', '!=', $get('from_wallet_id'));
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(function (Get $get, ?Model $record): bool {
                                if(!blank($record)) {
                                    if($record->isTransferTransaction) {
                                        return false;
                                    }
                                }
                                return $get('type') == TransactionTypeEnum::TRANSFER->value && !blank($get('from_wallet_id'));
                            }),
                        Toggle::make('confirmed')
                            ->label(__('transactions.fields.confirmed'))
                            ->default(true)
                            ->visible(fn (Get $get): bool => $get('type') != TransactionTypeEnum::TRANSFER->value),

                    ])->columns([
                        'sm' => 2,
                    ])->columnSpan([
                        'sm' => 2
                    ]),
                Forms\Components\Card::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        TextInput::make('meta.memo.note')
                            ->columnSpan(2),
                        FileUpload::make('meta.memo.attachment')
                            ->columnSpan(2),
                    ]),
            ])->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payable.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('wallet.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('confirmed')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index'  => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit'   => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
