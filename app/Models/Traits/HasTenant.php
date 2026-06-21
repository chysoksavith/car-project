<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use App\Models\Company;

trait HasTenant
{
    /**
     * Boot the trait for a model.
     */
    protected static function bootHasTenant(): void
    {
        // 1. Automatically scope all queries to the current user's company
        static::addGlobalScope(new TenantScope);

        // 2. Automatically set the company_id on record creation
        static::creating(function ($model) {
            if (auth()->hasUser() && auth()->user()->company_id && empty($model->company_id)) {
                $model->company_id = auth()->user()->company_id;
            }
        });
    }

    /**
     * Define the relationship to the Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
