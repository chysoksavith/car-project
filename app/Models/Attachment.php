<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'file_name',
        'file_path',
        'mime_type',
        'size',
        'disk',
        'collection_name',
    ];

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
