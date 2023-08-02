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

    /**
     * @throws Halt
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $type = ($data['type'] ?? null);
        if($type == TransactionTypeEnum::WITHDRAW->value) {
            $data['amount'] = $data['amount'] * -1;
            try {
                $this->validateCreditLimit($data);
            } catch (InsufficientFunds $exception) {
                Notification::make()
                    ->danger()
                    ->title("Insufficient funds")
                    ->send();
                $this->halt();
            }
        } elseif($type == TransactionTypeEnum::TRANSFER->value) {
            $this->createTransferTransaction($data);
            $this->sendCreatedNotificationAndRedirect(shouldCreateAnotherInsteadOfRedirecting: false);
            $this->halt();
        }

        return $data;
    }

    /**
     * @throws Halt
     */
    public function validateCreditLimit($data): void
    {
        $wallet = Wallet::findOrFail($data['wallet_id']);
        $amount = (double) $wallet->balance + ($data['amount']);
        $creditLimit = -1 * (double) $wallet->meta['credit'];

        if($amount < $creditLimit) {
            throw new InsufficientFunds('Insufficient funds');
        }
    }

    public function createTransferTransaction($data): void
    {
        $fromWallet = Wallet::findOrFail($data['from_wallet_id']);
        $toWallet = Wallet::findOrFail($data['to_wallet_id']);
        $transfer = $fromWallet->transfer($toWallet, $data['amount'], new Extra(
            deposit: ['transfer' => true, 'happened_at' => $data['happened_at'] ?? now()],
            withdraw: ['transfer' => true, 'happened_at' => $data['happened_at'] ?? now()],
        ));
        $this->record = $transfer->deposit;
    }
}
