<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\TransactionResource;
use App\Models\Wallet;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\External\Dto\Extra;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();
            if($data['type'] ?? null == TransactionTypeEnum::TRANSFER->value) {
                $this->createTransferTransaction($data);

                /** @internal Read the DocBlock above the following method. */
                $this->sendCreatedNotificationAndRedirect(shouldCreateAnotherInsteadOfRedirecting: $another);
            } else {
                parent::create($another);
            }
        } catch (Halt $exception) {
            return;
        } catch (InsufficientFunds $exception) {
            Notification::make()
                ->danger()
                ->title("Insufficient funds")
                ->send();
            return;
        }
    }

    public function createTransferTransaction($data): void
    {
        $fromWallet = Wallet::findOrFail($data['from_wallet_id']);
        $toWallet = Wallet::findOrFail($data['to_wallet_id']);
        $transfer = $fromWallet->transfer($toWallet, $data['amount'], new Extra(
            deposit: ['transfer' => true],
            withdraw: ['transfer' => true]
        ));
        $this->record = $transfer->deposit;
    }
}
