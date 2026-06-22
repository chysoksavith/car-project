<?php

namespace App\Models;

use App\Models\Traits\HasTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company_id',
    'name',
    'code',
    'description',
    'is_active',
    'sort_order',
])]
class Fuel extends Model
{
    use HasFactory, HasTenant;

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
