<?php

namespace App\Filament\Resources\DebtResource\Pages;

use App\Enums\DebtTypeEnum;
use App\Filament\Resources\DebtResource;
use App\Models\Debt;
use App\Models\Goal;
use App\Models\Wallet;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDebts extends ListRecords
{
    protected static string $resource = DebtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('deposit')
                ->label(__('debts.actions.debt_collection'))
                ->color('danger')
                ->icon('lucide-trending-up')
                ->form($this->getDebtTransactionFields())
                ->action(function (array $data) {
                    $this->makeGoalTransaction($data);
                }),
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->icon('helping-hand')
                ->badge(Debt::tenant()->count()),
            DebtTypeEnum::PAYABLE->value => Tab::make()
                ->icon('lucide-trending-down')
                ->badge(Debt::tenant()->where('type', DebtTypeEnum::PAYABLE->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', DebtTypeEnum::PAYABLE->value)),
            DebtTypeEnum::RECEIVABLE->value => Tab::make()
                ->icon('lucide-trending-up')
                ->badge(Debt::tenant()->where('type', DebtTypeEnum::RECEIVABLE->value)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', DebtTypeEnum::RECEIVABLE->value)),
        ];
    }

    public function getDebtTransactionFields($debtId = null): array
    {
        return [
            Hidden::make('debt_id')
                ->default($debtId)
                ->visible(fn() => !is_null($debtId)),
            Select::make('debt_id')
                ->label(__('debts.fields.debt'))
                ->options(Debt::all()->pluck('name', 'id')->toArray())
                ->visible(fn() => is_null($debtId))
                ->searchable()
                ->required(),
            Select::make('wallet_id')
                ->label(__('debts.fields.from_wallet'))
                ->options(Wallet::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),
            TextInput::make('amount')
                ->label(__('debts.fields.amount'))
                ->numeric()
                ->required(),
        ];
    }

    public function makeGoalTransaction($data): void
    {
        try {
            $wallet = Wallet::findOrFail($data['wallet_id']);
            $amount = (double) $data['amount'];

            $wallet->deposit($amount, [
                'reference_type' => Debt::class,
                'reference_id' => $data['debt_id'],
            ]);

            Notification::make()
                ->title('Saved successfully')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
