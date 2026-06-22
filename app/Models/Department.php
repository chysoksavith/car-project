<?php

namespace App\Models;

use App\Models\Traits\HasTenant;
use App\Models\Traits\TracksAuditCols;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'company_id',
    'name',
    'code',
    'is_active',
    'created_by',
    'updated_by',
    'deleted_by',
])]
class Department extends Model
{
    use HasFactory, HasTenant, SoftDeletes, TracksAuditCols;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
