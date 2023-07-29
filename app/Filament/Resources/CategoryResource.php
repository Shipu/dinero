<?php

namespace App\Filament\Resources;

use App\Enums\SpendTypeEnum;
use App\Enums\VisibilityStatusEnum;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Tables\Columns\IconColorColumn;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Guava\FilamentIconPicker\Tables\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->columns(2)
                    ->schema([
                        Radio::make('type')
                            ->label(__('categories.fields.type'))
                            ->columnSpan(2)
                            ->options(collect(__('categories.types'))->pluck('label', 'id')->reverse())
//                            ->descriptions(collect(__('categories.types'))->pluck('description', 'id'))
                            ->inline()
                            ->default(SpendTypeEnum::EXPENSE->value)
                            ->required(),
                        TextInput::make('name')
                            ->label(__('categories.fields.name'))
                            ->required()
                            ->maxLength(255),
                        ColorPicker::make('color')
                            ->label(__('categories.fields.color'))
                            ->default('#22b3e0'),
                        IconPicker::make('icon')
                            ->label(__('categories.fields.icon'))
//                            ->sets(['lucide-icons'])
                            ->sets(['heroicons', 'fontawesome-solid'])
                            ->columnSpan(2)
                            ->preload()
                            ->columns([
                                'default' => 1,
                                'lg' => 3,
                                '2xl' => 5,
                            ]),
                        Toggle::make('status')
                            ->label(__('categories.fields.is_visible'))
                            ->default(VisibilityStatusEnum::ACTIVE->value)
                            ->helperText(__('categories.fields.is_visible_help_text'))
                            ->afterStateHydrated(function (Toggle $component, string $state) {
                                $component->state($state == VisibilityStatusEnum::ACTIVE->value);
                            })
                            ->dehydrateStateUsing(fn (string $state): string => $state ? VisibilityStatusEnum::ACTIVE->value : VisibilityStatusEnum::INACTIVE->value)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColorColumn::make('icon')
                    ->label(__('categories.fields.icon')),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('categories.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('categories.fields.type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("categories.types.{$state}.label"))
                    ->searchable(),
//                Tables\Columns\IconColumn::make('icon')
//                    ->label(__('categories.fields.icon'))
//                    ->color(fn (string $state): string => match ($state) {
//                        default => '#22b3e0',
//                    })
//                    ->icon(fn (string $state): string => match ($state) {
//                        default => $state,
//                    }),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => __("categories.visibility_statuses.{$state}"))
                    ->searchable(),
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
//                Tables\Filters\TrashedFilter::make(),
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
            ->reorderable('order')
            ->defaultSort('order')
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
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
