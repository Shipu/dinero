<?php

namespace App\Transformer;

use Bavix\Wallet\Internal\Dto\TransactionDtoInterface;
use Bavix\Wallet\Internal\Transform\TransactionDtoTransformerInterface;
use Filament\Facades\Filament;

final class FilamentTransactionDtoTransformer implements TransactionDtoTransformerInterface
{
    public function extract(TransactionDtoInterface $dto): array
    {
        return [
            'happened_at' => $dto->getMeta()['happened_at'] ?? now(), // '2021-01-01 00:00:00
            'reference_type' => $dto->getMeta()['reference_type'] ?? null,
            'reference_id' => $dto->getMeta()['reference_id'] ?? null,
            'category_id' => $dto->getMeta()['category_id'] ?? null,
            'account_id' => optional(Filament::getTenant())->id ?? $dto->getMeta()['account_id'] ?? null,
            'uuid' => $dto->getUuid(),
            'payable_type' => $dto->getPayableType(),
            'payable_id' => $dto->getPayableId(),
            'wallet_id' => $dto->getWalletId(),
            'type' => $dto->getType(),
            'amount' => $dto->getAmount(),
            'confirmed' => $dto->isConfirmed(),
            'meta' => $dto->getMeta(),
            'created_at' => $dto->getCreatedAt(),
            'updated_at' => $dto->getUpdatedAt(),
        ];
    }
}