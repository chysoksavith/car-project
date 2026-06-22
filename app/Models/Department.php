<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory, \App\Models\Traits\HasTenant, \Illuminate\Database\Eloquent\SoftDeletes, \App\Models\Traits\TracksAuditCols;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
