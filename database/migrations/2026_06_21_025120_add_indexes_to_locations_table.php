<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->index('type', 'locations_type_idx');
            $table->index('parent_code', 'locations_parent_code_idx');
            $table->index(['type', 'parent_code'], 'locations_type_parent_idx');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex('locations_type_idx');
            $table->dropIndex('locations_parent_code_idx');
            $table->dropIndex('locations_type_parent_idx');
        });
    }
};
