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
        Schema::create('inspection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('inspection_items')->cascadeOnDelete();
            $table->string('name_kh');             // e.g. បូមមុខ ក្រោយ OR មេកានិច
            $table->string('name_en')->nullable(); // e.g. Front/Rear Brake Pump
            $table->decimal('default_price', 12, 2)->default(0); // Standard price in USD
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_items');
    }
};
