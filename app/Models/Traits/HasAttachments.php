<?php

namespace App\Models\Traits;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Services\FileUploadService;
use Illuminate\Http\UploadedFile;

trait HasAttachments
{
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Attach a file to this model
     */
    public function attachFile(UploadedFile $file, string $collectionName = 'default', string $disk = 'public'): Attachment
    {
        $fileUploadService = app(FileUploadService::class);
        $data = $fileUploadService->upload($file, $disk, 'attachments');

        return $this->attachments()->create([
            'file_name'       => $data['file_name'],
            'file_path'       => $data['file_path'],
            'mime_type'       => $data['mime_type'],
            'size'            => $data['size'],
            'disk'            => $data['disk'],
            'collection_name' => $collectionName,
        ]);
    }

    /**
     * Get the latest attachment for a given collection
     */
    public function getLatestAttachment(string $collectionName = 'default'): ?Attachment
    {
        return $this->attachments()->where('collection_name', $collectionName)->latest()->first();
    }
}
