<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasAttachments;

class Company extends Model
{
    use HasAttachments;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
