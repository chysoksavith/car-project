<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarInspectionResource extends JsonResource
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
            'car_id' => $this->car_id,
            'inspection_item_id' => $this->inspection_item_id,
            'cost' => (float) $this->cost,
            'condition' => $this->condition,
            'note' => $this->note,
            'inspection_item' => $this->whenLoaded('inspectionItem', function () {
                return [
                    'id' => $this->inspectionItem->id,
                    'name_kh' => $this->inspectionItem->name_kh,
                    'name_en' => $this->inspectionItem->name_en,
                    'default_price' => $this->inspectionItem->default_price,
                    'parent_id' => $this->inspectionItem->parent_id,
                    'parent' => $this->whenLoaded('inspectionItem.parent', function () {
                        return [
                            'id' => $this->inspectionItem->parent->id,
                            'name_kh' => $this->inspectionItem->parent->name_kh,
                            'name_en' => $this->inspectionItem->parent->name_en,
                        ];
                    }),
                ];
            }),
        ];
    }
}
