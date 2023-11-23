<?php

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
        Schema::create('matures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->date('mature_date');
            $table->decimal('expected_amount', 64, 0)->default(0);
            $table->boolean('is_paid')->default(false);
            $table->decimal('actual_amount', 64, 0)->default(0);
            $table->foreignIdFor(\App\Models\Account::class)->constrained(
                    (new \App\Models\Account())->getTable()
                )->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matures');
    }
};
