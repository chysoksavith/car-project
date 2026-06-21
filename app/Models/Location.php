<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name_km',
        'name_en',
        'type',
        'type_km',
        'type_en',
        'parent_code',
        'reference',
    ];

    /**
     * Get the parent location (e.g. Commune for a Village).
     */
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_code', 'code');
    }

    /**
     * Get the child locations (e.g. Villages for a Commune).
     */
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_code', 'code');
    }
}
