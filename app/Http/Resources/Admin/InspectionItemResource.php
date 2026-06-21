<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'parent_id'     => $this->parent_id,
            'name_kh'       => $this->name_kh,
            'name_en'       => $this->name_en,
            'default_price' => $this->default_price,
            'is_active'     => $this->is_active,
            'parent'        => $this->whenLoaded('parent', function () {
                return [
                    'id'      => $this->parent->id,
                    'name_kh' => $this->parent->name_kh,
                ];
            }),
        ];
    }
}
