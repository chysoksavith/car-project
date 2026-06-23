<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\CarResource;

class ContainerResource extends JsonResource
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
            'status' => $this->status,
            'departure_date' => $this->departure_date ? $this->departure_date->format('Y-m-d') : null,
            'expected_date' => $this->expected_date ? $this->expected_date->format('Y-m-d') : null,
            'note' => $this->note,
            'total_shipping_cost' => $this->total_shipping_cost,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'cars' => CarResource::collection($this->whenLoaded('cars')),
        ];
    }
}
