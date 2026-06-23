<?php

namespace App\Http\Requests\Admin\Car;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('cars.edit');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'options' => ['nullable', 'string'],
            'maker_id' => ['nullable', 'exists:makers,id'],
            'car_model_id' => ['nullable', 'exists:car_models,id'],
            'container_id' => ['nullable', 'exists:containers,id'],
            'fuel_id' => ['nullable', 'exists:fuels,id'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color_id' => ['nullable', 'exists:colors,id'],
            'condition' => ['required', 'in:NEW,USED'],
            'transmission' => ['nullable', 'string', 'max:255'],
            'body_number' => ['nullable', 'string', 'max:255'],
            'engine_number' => ['nullable', 'string', 'max:255'],
            'engine_capacity_cc' => ['nullable', 'integer', 'min:0'],
            'registration_type' => ['required', 'in:TAX_PAPER,PLATE_NUMBER'],
            'plate_number' => ['nullable', 'string', 'max:255'],
            'certificate_number' => ['nullable', 'string', 'max:255'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'inventory_status' => ['required', 'in:IN_TRANSIT,IN_SHOWROOM,DELIVERED'],
            'sales_status' => ['required', 'in:AVAILABLE,BOOKED,RESERVED,SOLD'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'cif_price' => ['nullable', 'numeric', 'min:0'],
            'transport_cost' => ['nullable', 'numeric', 'min:0'],
            'expected_profit' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'discounted_price' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
            'is_published' => ['boolean'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'max:5120'],
            'deleted_images' => ['nullable', 'array'],
            'deleted_images.*' => ['integer'],
        ];
    }
}
