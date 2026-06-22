<?php

namespace App\Services;

use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShipmentService
{
    // # Create Shipment
    public function createShipment(array $data): Shipment
    {
        DB::beginTransaction();
        try {
            // company_id will be automatically filled by HasTenant trait or user's session
            $shipment = Shipment::create($data);
            DB::commit();

            return $shipment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create shipment: ' . $e->getMessage());
            throw $e;
        }
    }

    // # Update Shipment
    public function updateShipment(Shipment $shipment, array $data): bool
    {
        DB::beginTransaction();
        try {
            $updated = $shipment->update($data);
            DB::commit();

            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update shipment: ' . $e->getMessage());
            throw $e;
        }
    }

    // # Delete Shipment
    public function deleteShipment(Shipment $shipment): bool
    {
        DB::beginTransaction();
        try {
            $deleted = $shipment->delete();
            DB::commit();

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete shipment: ' . $e->getMessage());
            throw $e;
        }
    }
}
