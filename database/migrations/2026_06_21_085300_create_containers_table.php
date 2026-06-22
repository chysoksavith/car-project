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
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('ship_id')->nullable();
            $table->string('bl_number');
            $table->string('container_number')->unique();
            $table->string('container_type')->nullable();
            $table->string('status')->default('on_the_way');
            $table->date('departure_date')->nullable();
            $table->date('expected_date')->nullable();
            $table->string('video_review_arrival')->nullable();
            $table->text('note')->nullable();
            $table->decimal('total_shipping_cost', 12, 2)->nullable();
            $table->string('cost_allocation_method')->default('equal_split');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
