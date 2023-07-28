<?php

declare(strict_types=1);

use App\Enums\TransferStatusEnum;
use App\Models\Account;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Transfer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create($this->table(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Account::class)->constrained((new Account())->getTable())->cascadeOnDelete();
            $table->morphs('from');
            $table->morphs('to');
            $table->string('status')->comment(implode(',', TransferStatusEnum::toArray()))->default(TransferStatusEnum::TRANSFER->value);
            $table->string('status_last')->comment(implode(',', TransferStatusEnum::toArray()))->nullable();

            $table->unsignedBigInteger('deposit_id');
            $table->unsignedBigInteger('withdraw_id');

            $table->decimal('discount', 64, 0)
                ->default(0);

            $table->decimal('fee', 64, 0)
                ->default(0);

            $table->uuid('uuid')
                ->unique();
            $table->text('description')->nullable();
            $table->timestamp('happened_at')->default(now());
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('deposit_id')
                ->references('id')
                ->on($this->transactionTable())
                ->onDelete('cascade')
            ;

            $table->foreign('withdraw_id')
                ->references('id')
                ->on($this->transactionTable())
                ->onDelete('cascade')
            ;
        });
    }

    public function down(): void
    {
        Schema::drop($this->table());
    }

    private function table(): string
    {
        return (new Transfer())->getTable();
    }

    private function transactionTable(): string
    {
        return (new Transaction())->getTable();
    }
};
