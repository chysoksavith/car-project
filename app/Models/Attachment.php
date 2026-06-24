<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'attachable_type',
    'attachable_id',
    'file_name',
    'file_path',
    'mime_type',
    'size',
    'disk',
    'collection_name',
    'sort_order',
])]
class Attachment extends Model
{

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the absolute URL to the file
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->file_path);
    }
}
