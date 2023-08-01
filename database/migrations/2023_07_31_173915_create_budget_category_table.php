<?php

use App\Models\Budget;
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
        Schema::create('budget_category', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Budget::class)->constrained((new Budget())->getTable())->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained((new Category())->getTable())->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_category');
    }
};
