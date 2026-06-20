<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['house_no', 'road_no', 'village', 'commune', 'district', 'province', 'address'])]
class Address extends Model
{
    protected function casts(): array
    {
        return [
            'address' => 'json',
        ];
    }

    public function addressable()
    {
        return $this->morphTo();
    }
}
