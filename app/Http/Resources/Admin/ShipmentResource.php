<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'supplier_id' => $this->supplier_id,
            'ship_id' => $this->ship_id,
            'bl_number' => $this->bl_number,
            'container_number' => $this->container_number,
            'container_type' => $this->container_type,
            'status' => $this->status,
            'departure_date' => $this->departure_date ? $this->departure_date->format('Y-m-d') : null,
            'expected_date' => $this->expected_date ? $this->expected_date->format('Y-m-d') : null,
            'video_review_arrival' => $this->video_review_arrival,
            'note' => $this->note,
            'total_shipping_cost' => $this->total_shipping_cost,
            'cost_allocation_method' => $this->cost_allocation_method,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
