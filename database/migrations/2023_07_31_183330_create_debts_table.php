<?php

use App\Models\Account;
use App\Models\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->unsignedDecimal('amount')->default(0);
            $table->text('description')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->foreignIdFor(Account::class)->constrained((new Account())->getTable())->cascadeOnDelete();
            $table->foreignIdFor(Wallet::class)->constrained((new Wallet())->getTable())->cascadeOnDelete();
            $table->string('color')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
