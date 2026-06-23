<?php

namespace App\Models;

use App\Enums\CarCondition;
use App\Enums\InventoryStatus;
use App\Enums\RegistrationType;
use App\Enums\SalesStatus;
use App\Enums\Transmission;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HasTenant;
use App\Models\Traits\TracksAuditCols;
use App\Models\Traits\HasAttachments;

#[Fillable([
    'name',
    'slug',
    'description',
    'company_id',
    'maker_id',
    'car_model_id',
    'container_id',
    'fuel_id',
    'year',
    'color_id',
    'condition',
    'transmission',
    'body_number',
    'engine_number',
    'engine_capacity_cc',
    'registration_type',
    'plate_number',
    'certificate_number',
    'quantity',
    'view_count',
    'in_stock_at',
    'inventory_status',
    'sales_status',
    'purchase_price',
    'cif_price',
    'transport_cost',
    'expected_profit',
    'selling_price',
    'discounted_price',
    'sold_at',
    'is_active',
    'is_published',
    'options',
    'created_by',
    'updated_by',
])]
class Car extends Model
{
    use HasFactory, SoftDeletes, HasTenant, TracksAuditCols, HasAttachments;

    // # Define type casting for model attributes
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'engine_capacity_cc' => 'integer',
            'quantity' => 'integer',
            'view_count' => 'integer',
            'in_stock_at' => 'datetime',
            'sold_at' => 'datetime',
            'is_active' => 'boolean',
            'is_published' => 'boolean',
            'purchase_price' => 'decimal:2',
            'cif_price' => 'decimal:2',
            'transport_cost' => 'decimal:2',
            'expected_profit' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'discounted_price' => 'decimal:2',
            'condition' => CarCondition::class,
            'transmission' => Transmission::class,
            'registration_type' => RegistrationType::class,
            'inventory_status' => InventoryStatus::class,
            'sales_status' => SalesStatus::class,
        ];
    }

    public function maker(): BelongsTo
    {
        return $this->belongsTo(Maker::class);
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function fuel(): BelongsTo
    {
        return $this->belongsTo(Fuel::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}
