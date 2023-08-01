<?php

declare(strict_types=1);

use App\Enums\WalletTypeEnum;
use App\Models\Account;
use App\Models\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create($this->table(), static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('holder');
            $table->string('name');
            $table->string('slug')
                ->index();
            $table->uuid('uuid')
                ->unique();
            $table->foreignIdFor(Account::class)->constrained((new Account())->getTable())->cascadeOnDelete();
            $table->string('type')->default(WalletTypeEnum::GENERAL->value);
            $table->string('currency_code')->default('USD');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('exclude')->default(false);
            $table->unsignedSmallInteger('statement_day_of_month')->nullable();
            $table->unsignedSmallInteger('payment_due_day_of_month')->nullable();
            $table->string('description')
                ->nullable();
            $table->json('meta')
                ->nullable();
            $table->decimal('balance', 64, 0)
                ->default(0);
            $table->unsignedSmallInteger('decimal_places')
                ->default(2)
            ;
            $table->timestamps();

            $table->unique(['holder_type', 'holder_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::drop($this->table());
    }

    private function table(): string
    {
        return (new Wallet())->getTable();
    }
};
