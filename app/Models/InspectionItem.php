<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Traits\HasTenant;

#[Fillable(['company_id', 'parent_id', 'name_kh', 'name_en', 'default_price', 'is_active'])]
class InspectionItem extends Model
{
    use HasFactory, HasTenant;

    protected function casts(): array
    {
        return [
            'default_price' => 'decimal:2',
            'is_active'     => 'boolean',
        ];
    }

    public function carInspections()
    {
        return $this->hasMany(CarInspection::class, 'inspection_item_id');
    }

    public function parent()
    {
        return $this->belongsTo(InspectionItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(InspectionItem::class, 'parent_id');
    }
}
