<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FileUploadService
{
    // # Upload
    public function upload(UploadedFile $file, string $disk = 'public', string $path = 'attachments'): array
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        $fullPath = rtrim($path, '/') . '/' . $fileName;

        // Optional: Perform image compression for images
        $isImage = str_starts_with($file->getMimeType(), 'image/');
        
        if ($isImage && in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'])) {
            $manager = new ImageManager(new Driver());
            $image = $manager->decode($file->getPathname());
            
            // Auto orient and scale down if too large, then compress
            $image->scaleDown(width: 1920);
            $encoded = $image->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: 80));
            
            $fileName = Str::uuid() . '.webp';
            $fullPath = rtrim($path, '/') . '/' . $fileName;
            
            Storage::disk($disk)->put($fullPath, $encoded->toString());
            $size = strlen($encoded->toString());
            $mimeType = 'image/webp';
        } else {
            Storage::disk($disk)->putFileAs($path, $file, $fileName);
            $size = $file->getSize();
            $mimeType = $file->getMimeType();
        }

        return [
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $fullPath,
            'mime_type' => $mimeType,
            'size'      => $size,
            'disk'      => $disk,
        ];
    }
}
