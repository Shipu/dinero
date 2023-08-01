<?php

use App\Models\Account;
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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Account::class)->constrained((new Account())->getTable())->cascadeOnDelete();
            $table->unsignedDecimal('amount')->default(0);
            $table->timestamp('target_date')->nullable();
            $table->string('color')->nullable();
            $table->string('currency_code')->default('USD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
