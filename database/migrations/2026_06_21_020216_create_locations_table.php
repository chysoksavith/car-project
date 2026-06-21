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
        Schema::create('locations', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->string('name_km');
            $table->string('name_en');
            $table->string('type')->index();
            $table->string('type_km')->nullable();
            $table->string('type_en')->nullable();
            $table->string('parent_code')->nullable()->index();
            $table->string('reference')->nullable();
            $table->timestamps();

            $table->index('name_km');
            $table->index('name_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
