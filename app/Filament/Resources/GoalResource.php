<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoalResource\Pages;
use App\Filament\Resources\GoalResource\RelationManagers;
use App\Models\Goal;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GoalResource extends Resource
{
    protected static ?string $model = Goal::class;

    protected static ?string $navigationIcon = 'goal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('goals.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'sm' => 2,
                            ]),
                        TextInput::make('amount')
                            ->label(__('goals.fields.amount'))
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        DateTimePicker::make('target_date')
                            ->label(__('goals.fields.target_date'))
                            ->required()
                            ->default(now()->addMonth()),
                        Select::make('currency_code')
                            ->label(__('goals.fields.currency_code'))
                            ->options(config('utilities.currencies'))
                            ->default('BDT'),
                        ColorPicker::make('color')
                            ->label(__('goals.fields.color'))
                            ->default('#22b3e0'),
                    ])->columns(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('color')
                    ->label(__('goals.fields.color'))
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('goals.fields.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->label(__('goals.fields.amount'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('target_date')
                    ->label(__('goals.fields.target_date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('currency_code')
                    ->label(__('goals.fields.currency_code'))
                    ->formatStateUsing(fn (string $state): string => config("utilities.currencies.{$state}"))
                    ->searchable(),
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
            'index' => Pages\ListGoals::route('/'),
            'create' => Pages\CreateGoal::route('/create'),
            'edit' => Pages\EditGoal::route('/{record}/edit'),
        ];
    }    
}
