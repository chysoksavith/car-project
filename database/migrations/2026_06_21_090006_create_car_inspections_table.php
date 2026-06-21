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
        Schema::create('car_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('car_id')->nullable(); // FK to cars table (future)
            $table->foreignId('inspection_item_id')->constrained('inspection_items')->cascadeOnDelete();
            $table->decimal('cost', 12, 2)->default(0);       // Actual repair cost
            $table->string('condition')->nullable();          // e.g. "needs_replacement", "replaced", "good"
            $table->text('note')->nullable();                 // Any specific mechanic notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_inspections');
    }
};
