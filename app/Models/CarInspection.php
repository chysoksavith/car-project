<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Traits\HasTenant;

#[Fillable(['company_id', 'car_id', 'inspection_item_id', 'cost', 'condition', 'note'])]
class CarInspection extends Model
{
    use HasFactory, HasTenant;

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
        ];
    }

    public function inspectionItem()
    {
        return $this->belongsTo(InspectionItem::class, 'inspection_item_id');
    }
}
