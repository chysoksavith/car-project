<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'maker_id' => $this->maker_id,
            'maker' => $this->whenLoaded('maker', fn() => [
                'id' => $this->maker->id,
                'name' => $this->maker->name,
            ]),
            'car_model_id' => $this->car_model_id,
            'car_model' => $this->whenLoaded('carModel', fn() => [
                'id' => $this->carModel->id,
                'name' => $this->carModel->name,
            ]),
            'container_id' => $this->container_id,
            'container' => $this->whenLoaded('container', fn() => [
                'id' => $this->container->id,
                'container_number' => $this->container->container_number,
            ]),
            'fuel_id' => $this->fuel_id,
            'fuel' => $this->whenLoaded('fuel', fn() => [
                'id' => $this->fuel->id,
                'name' => $this->fuel->name,
            ]),
            'year' => $this->year,
            'color_id' => $this->color_id,
            'color' => $this->whenLoaded('color', fn() => [
                'id' => $this->color->id,
                'name' => $this->color->name,
                'hex_code' => $this->color->hex_code,
            ]),
            'condition' => $this->condition,
            'transmission' => $this->transmission,
            'body_number' => $this->body_number,
            'engine_number' => $this->engine_number,
            'engine_capacity_cc' => $this->engine_capacity_cc,
            'registration_type' => $this->registration_type,
            'plate_number' => $this->plate_number,
            'certificate_number' => $this->certificate_number,
            'quantity' => $this->quantity,
            'view_count' => $this->view_count,
            'in_stock_at' => $this->in_stock_at,
            'inventory_status' => $this->inventory_status,
            'sales_status' => $this->sales_status,
            'purchase_price' => $this->purchase_price,
            'cif_price' => $this->cif_price,
            'transport_cost' => $this->transport_cost,
            'expected_profit' => $this->expected_profit,
            'selling_price' => $this->selling_price,
            'discounted_price' => $this->discounted_price,
            'sold_at' => $this->sold_at,
            'is_active' => $this->is_active,
            'is_published' => $this->is_published,
            'images' => $this->whenLoaded('attachments', fn() => $this->attachments->map(fn($attachment) => [
                'id' => $attachment->id,
                'url' => $attachment->url,
                'file_name' => $attachment->file_name,
            ])),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
