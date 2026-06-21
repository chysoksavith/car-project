<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Traits\HasTenant;

#[Fillable(['company_id', 'name', 'contact_person', 'phone', 'email', 'address', 'is_active'])]
class Supplier extends Model
{
    use HasFactory, HasTenant;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
