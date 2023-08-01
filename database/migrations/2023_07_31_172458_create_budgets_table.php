<?php

use App\Enums\BudgetPeriodEnum;
use App\Models\Account;
use App\Models\Category;
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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedDecimal('amount')->default(0);
            $table->foreignIdFor(Account::class)->constrained((new Account())->getTable())->cascadeOnDelete();
            $table->string('color')->nullable();
            $table->string('period')->comment(implode(',', BudgetPeriodEnum::toArray()));
            $table->string('day_of_week')->nullable();
            $table->string('day_of_month')->nullable();
            $table->string('month_of_quarter')->nullable();
            $table->string('month_of_year')->nullable();
            $table->string('status')->default(\App\Enums\VisibilityStatusEnum::ACTIVE->value);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
