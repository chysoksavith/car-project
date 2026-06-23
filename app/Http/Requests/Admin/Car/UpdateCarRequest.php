<?php

namespace App\Http\Requests\Admin\Car;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'condition' => ['required', Rule::enum(\App\Enums\CarCondition::class)],
            'transmission' => ['nullable', Rule::enum(\App\Enums\Transmission::class)],
            'body_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('cars', 'body_number')->ignore($this->route('car')->id),
            ],
            'engine_number' => ['nullable', 'string', 'max:255'],
            'engine_capacity_cc' => ['nullable', 'integer', 'min:0'],
            'registration_type' => ['required', Rule::enum(\App\Enums\RegistrationType::class)],
            'plate_number' => ['nullable', 'string', 'max:255'],
            'certificate_number' => ['nullable', 'string', 'max:255'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'inventory_status' => ['required', Rule::enum(\App\Enums\InventoryStatus::class)],
            'sales_status' => ['required', Rule::enum(\App\Enums\SalesStatus::class)],
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
