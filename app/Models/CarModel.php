<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Traits\HasTenant;

#[Fillable(['company_id', 'maker_id', 'name', 'is_active'])]
class CarModel extends Model
{
    use HasFactory, HasTenant;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }
}
