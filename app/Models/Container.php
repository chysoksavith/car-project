<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Traits\HasTenant;
use App\Models\Traits\TracksAuditCols;

#[Fillable([
    'company_id',
    'supplier_id',
    'ship_id',
    'bl_number',
    'container_number',
    'container_type',
    'status',
    'departure_date',
    'expected_date',
    'note',
    'total_shipping_cost',
    'created_by',
    'updated_by',
    'deleted_by',
])]
class Container extends Model
{
    use HasFactory, SoftDeletes, HasTenant, TracksAuditCols;

    protected function casts(): array
    {
        return [
            'status'              => \App\Enums\ContainerStatus::class,
            'departure_date'      => 'date',
            'expected_date'       => 'date',
            'total_shipping_cost' => 'decimal:2',
        ];
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Supplier::class);
    }

    public function cars()
    {
        return $this->hasMany(\App\Models\Car::class);
    }

    // Audit relations are provided by TracksAuditCols:
    // creator(), updater(), deleter()
}
