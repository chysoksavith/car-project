<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['first_name', 'last_name', 'status', 'telegram_username'])]
class Customer extends Model
{
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
