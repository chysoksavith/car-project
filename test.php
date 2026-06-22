<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('stock_code')->nullable()->unique();
            $table->string('external_car_id')->nullable();
            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */
            $table->foreignId('brand_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('car_model_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('auto_type_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('container_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('sale_letter_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('fuel_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Vehicle Information
            |--------------------------------------------------------------------------
            */
            $table->year('year')->nullable();

            $table->string('color')->nullable();

            $table->enum('condition', ['NEW', 'USED'])->default('NEW');


            $table->string('transmission')->nullable();
            // Automatic, Manual, CVT

            $table->string('body_number')->nullable();

            $table->string('vin_number')->nullable()->index();

            $table->string('engine_number')->nullable();

            $table->unsignedInteger('engine_capacity_cc')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Registration Information
            |--------------------------------------------------------------------------
            */
            $table->enum('registration_type', [
                'TAX_PAPER',
                'PLATE_NUMBER',
            ])->default('TAX_PAPER');

            $table->string('plate_number')->nullable();

            $table->string('certificate_number')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Inventory
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('view_count')->default(0);

            $table->timestamp('in_stock_at')->nullable();

            $table->enum('inventory_status', [
                'IN_TRANSIT',
                'IN_SHOWROOM',
                'DELIVERED',
            ])->default('IN_TRANSIT');

            $table->enum('sales_status', [
                'AVAILABLE',
                'RESERVED',
                'SOLD',
            ])->default('AVAILABLE');

            /*
            |--------------------------------------------------------------------------
            | Pricing & Costs
            |--------------------------------------------------------------------------
            */
            $table->decimal('purchase_price', 15, 2)
                ->default(0); // Vehicle buying cost

            $table->decimal('cif_price', 15, 2)
                ->nullable(); // Cost, Insurance, Freight

            $table->decimal('transport_cost', 15, 2)
                ->default(0); // Transport/Logistics cost

            $table->decimal('service_cost', 15, 2)
                ->default(0); // Repair/Maintenance cost

            $table->decimal('expected_profit', 15, 2)
                ->default(0); // Target money profit

            $table->decimal('selling_price', 15, 2)
                ->default(0);

            $table->decimal('discounted_price', 15, 2)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Sales Information
            |--------------------------------------------------------------------------
            */
            $table->timestamp('sold_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | System & Public Display
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')
                ->default(true);

            $table->boolean('is_published')
                ->default(false);


            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('inventory_status');
            $table->index('sales_status');
            $table->index('brand_id');
            $table->index('car_model_id');
            $table->index('year');
            $table->index(['inventory_status', 'car_model_id']);
            $table->index(['inventory_status', 'name']);
            $table->index('fuel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
