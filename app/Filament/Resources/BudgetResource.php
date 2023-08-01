<?php

namespace App\Filament\Resources;

use App\Enums\BudgetPeriodEnum;
use App\Enums\SpendTypeEnum;
use App\Enums\VisibilityStatusEnum;
use App\Filament\Resources\BudgetResource\Pages;
use App\Filament\Resources\BudgetResource\RelationManagers;
use App\Models\Budget;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static ?string $navigationIcon = 'lucide-calculator';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('budgets.fields.name'))
                            ->required()
                            ->maxLength(255),
                        ColorPicker::make('color')
                            ->label(__('budgets.fields.color'))
                            ->default('#22b3e0'),
                        TextInput::make('amount')
                            ->label(__('budgets.fields.amount'))
                            ->required()
                            ->numeric()
                            ->default(0.00)
                            ->columnSpan([
                                'sm' => 2,
                            ]),
                        Select::make('categories')
                            ->label(__('budgets.fields.categories'))
                            ->required()
                            ->relationship(
                                name: 'categories',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('type', SpendTypeEnum::EXPENSE->value),
                            )
                            ->searchable()
                            ->preload()
                            ->multiple()
                            ->columnSpan([
                                'sm' => 2,
                            ]),
                        Fieldset::make(__('budgets.fields.recurrence'))
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Select::make('period')
                                            ->label(__('budgets.fields.period'))
                                            ->options(__('budgets.periods'))
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->live(),
                                    ])->columnSpan([
                                        'sm' => 2,
                                    ]),
                                Select::make('day_of_week')
                                    ->label(__('budgets.fields.day_of_week'))
                                    ->options(__('utilities.weekdays'))
                                    ->placeholder(collect(__('utilities.weekdays'))->first())
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn (Get $get) => $get('period') === BudgetPeriodEnum::WEEKLY->value),
                                Select::make('day_of_month')
                                    ->label(__('budgets.fields.day_of_month'))
                                    ->options(config('utilities.month_ordinal_numbers'))
                                    ->searchable()
                                    ->preload()
                                    ->placeholder(config('utilities.month_ordinal_numbers')->first())
                                    ->visible(fn (Get $get) => in_array($get('period'), BudgetPeriodEnum::toArrayExcept([BudgetPeriodEnum::WEEKLY->value]))),
                                Select::make('month_of_quarter')
                                    ->label(__('budgets.fields.month_of_quarter'))
                                    ->options(__('utilities.quarter_months'))
                                    ->preload()
                                    ->searchable()
                                    ->visible(fn (Get $get) => $get('period') === BudgetPeriodEnum::QUARTERLY->value),
                                Select::make('month_of_year')
                                    ->label(__('budgets.fields.month_of_year'))
                                    ->options(__('utilities.months'))
                                    ->preload()
                                    ->searchable()
                                    ->visible(fn (Get $get) => $get('period') === BudgetPeriodEnum::YEARLY->value),
                            ]),
                        Toggle::make('status')
                            ->required()
                            ->label(__('budgets.fields.enabled'))
                            ->default(VisibilityStatusEnum::ACTIVE->value)
                            ->helperText(__('budgets.fields.enabled_help_text'))
                            ->afterStateHydrated(function (Toggle $component, string $state) {
                                $component->state($state == VisibilityStatusEnum::ACTIVE->value);
                            })
                            ->dehydrateStateUsing(fn (string $state): string => $state ? VisibilityStatusEnum::ACTIVE->value : VisibilityStatusEnum::INACTIVE->value),
                    ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('budgets.fields.color'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('budgets.fields.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('budgets.fields.actual_amount'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('spend_amount')
                    ->label(__('budgets.fields.spend_amount'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label(__('budgets.fields.enabled'))
                    ->icon(fn (string $state): string => match ($state) {
                        VisibilityStatusEnum::ACTIVE->value => 'lucide-check-circle',
                        VisibilityStatusEnum::INACTIVE->value => 'lucide-x-circle',
                        default => 'lucide-x-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        VisibilityStatusEnum::ACTIVE->value => 'success',
                        VisibilityStatusEnum::INACTIVE->value => 'danger',
                        default => 'gray',
                    }),
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
            'index' => Pages\ListBudgets::route('/'),
            'create' => Pages\CreateBudget::route('/create'),
            'edit' => Pages\EditBudget::route('/{record}/edit'),
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
