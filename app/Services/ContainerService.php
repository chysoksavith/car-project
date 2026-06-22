<?php

namespace App\Services;

use App\Models\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContainerService
{
    // # Create Container
    public function createContainer(array $data): Container
    {
        DB::beginTransaction();
        try {
            // company_id will be automatically filled by HasTenant trait or user's session
            $container = Container::create($data);
            DB::commit();

            return $container;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create container: ' . $e->getMessage());
            throw $e;
        }
    }

    // # Update Container
    public function updateContainer(Container $container, array $data): bool
    {
        DB::beginTransaction();
        try {
            $updated = $container->update($data);
            DB::commit();

            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update container: ' . $e->getMessage());
            throw $e;
        }
    }

    // # Delete Container
    public function deleteContainer(Container $container): bool
    {
        DB::beginTransaction();
        try {
            $deleted = $container->delete();
            DB::commit();

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete container: ' . $e->getMessage());
            throw $e;
        }
    }
}
