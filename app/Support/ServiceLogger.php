<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

/**
 * Structured logging utility for service-layer operations.
 *
 * Usage:
 *   ServiceLogger::created('Container', $id, ['bl_number' => '...']);
 *   ServiceLogger::updated('Container', $id, ['status' => 'arrived']);
 *   ServiceLogger::deleted('Container', $id);
 *   ServiceLogger::failed('Container', 'create', $exception);
 */
class ServiceLogger
{
    public static function created(string $resource, int|string $id, array $context = []): void
    {
        Log::info("[{$resource}] Created", array_merge([
            'action'      => 'create',
            'resource'    => $resource,
            'resource_id' => $id,
            'performed_by' => auth()->id(),
        ], $context));
    }

    public static function updated(string $resource, int|string $id, array $context = []): void
    {
        Log::info("[{$resource}] Updated", array_merge([
            'action'      => 'update',
            'resource'    => $resource,
            'resource_id' => $id,
            'performed_by' => auth()->id(),
        ], $context));
    }

    public static function deleted(string $resource, int|string $id, array $context = []): void
    {
        Log::info("[{$resource}] Soft-deleted", array_merge([
            'action'      => 'delete',
            'resource'    => $resource,
            'resource_id' => $id,
            'performed_by' => auth()->id(),
        ], $context));
    }

    public static function failed(string $resource, string $action, \Throwable $e, array $context = []): void
    {
        Log::error("[{$resource}] Failed to {$action}", array_merge([
            'action'      => $action,
            'resource'    => $resource,
            'performed_by' => auth()->id(),
            'error'       => $e->getMessage(),
            'file'        => $e->getFile(),
            'line'        => $e->getLine(),
        ], $context));
    }
}
