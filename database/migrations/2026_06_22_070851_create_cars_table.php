<?php

use App\Enums\CarCondition;
use App\Enums\InventoryStatus;
use App\Enums\RegistrationType;
use App\Enums\SalesStatus;
use App\Enums\Transmission;
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
            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */
            $table->foreignId('company_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('maker_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('car_model_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();


            $table->foreignId('container_id')
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

            $table->foreignId('color_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('condition', CarCondition::cases())->default(CarCondition::NEW->value);

            $table->enum('transmission', Transmission::cases())->nullable();

            $table->string('body_number')->nullable();


            $table->string('engine_number')->nullable();

            $table->unsignedInteger('engine_capacity_cc')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Registration Information
            |--------------------------------------------------------------------------
            */
            $table->enum('registration_type', RegistrationType::cases())->default(RegistrationType::TAX_PAPER->value);

            $table->string('plate_number')->nullable();

            $table->string('certificate_number')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Inventory
            |--------------------------------------------------------------------------
            */
            $table->integer('quantity')->nullable()->default(1);
            $table->unsignedInteger('view_count')->default(0);

            $table->timestamp('in_stock_at')->nullable();

            $table->enum('inventory_status', InventoryStatus::cases())->default(InventoryStatus::IN_TRANSIT->value);

            $table->enum('sales_status', SalesStatus::cases())->default(SalesStatus::AVAILABLE->value);

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
            $table->index('maker_id');
            $table->index('car_model_id');
            $table->index('year');
            $table->index(['inventory_status', 'car_model_id']);
            $table->index(['inventory_status', 'name']);
            $table->index('fuel_id');

            // Additional indexes for fast filtering and search performance
            $table->index('selling_price');
            $table->index('transmission');
            $table->index('condition');
            $table->index('plate_number');

            // Composite indexes for common multi-column WHERE clauses
            $table->index(['company_id', 'is_active', 'is_published', 'sales_status'], 'company_active_published_sales_idx');
            $table->index(['maker_id', 'car_model_id'], 'maker_model_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
