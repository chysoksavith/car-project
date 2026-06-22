<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasAttachments;

#[Fillable([
    'name',
    'email',
    'phone',
    'address',
    'is_active',
])]
class Company extends Model
{
    use HasAttachments;

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
