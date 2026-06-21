<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Traits\HasTenant;

#[Fillable(['company_id', 'name', 'hex_code', 'is_active'])]
class Color extends Model
{
    use HasFactory, HasTenant;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
