<?php

namespace App\Filament\Resources\GoalResource\Pages;

use App\Filament\Resources\GoalResource;
use App\Models\Goal;
use App\Models\Wallet;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListGoals extends ListRecords
{
    protected static string $resource = GoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('deposit')
                ->label(__('goals.actions.deposit'))
                ->color('danger')
                ->icon('lucide-trending-up')
                ->form($this->getGoalTransactionFields())
                ->action(function (array $data) {
                    $this->makeGoalTransaction($data);
                }),
            Action::make('withdraw')
                ->label(__('goals.actions.withdraw'))
                ->color('warning')
                ->icon('lucide-trending-down')
                ->form($this->getGoalTransactionFields('withdraw'))
                ->action(function (array $data) {
                    $this->makeGoalTransaction($data);
                }),
            Actions\CreateAction::make(),
        ];
    }

    public function getGoalTransactionFields($type = 'deposit', $goalId = null): array
    {
        return [
            Hidden::make('type')
                ->default($type),
            Hidden::make('goal_id')
                ->default($goalId)
                ->visible(fn() => !is_null($goalId)),
            Select::make('goal_id')
                ->label(__('goals.fields.goal'))
                ->options(Goal::all()->pluck('name', 'id')->toArray())
                ->visible(fn() => is_null($goalId))
                ->searchable()
                ->required(),
            Select::make('wallet_id')
                ->label(__('goals.fields.from_wallet'))
                ->options(Wallet::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),
            TextInput::make('amount')
                ->label(__('goals.fields.amount'))
                ->numeric()
                ->required(),
        ];
    }

    public function makeGoalTransaction($data): void
    {
        try {
            $wallet = Wallet::findOrFail($data['wallet_id']);
            $amount = (double) $data['amount'];
            $method = 'withdraw';
            if($data['type'] == 'withdraw') {
                $method = 'deposit';
            }

            $wallet->{$method}($amount, [
                'reference_type' => Goal::class,
                'reference_id' => $data['goal_id'],
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
