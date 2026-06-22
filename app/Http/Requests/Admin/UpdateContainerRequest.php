<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContainerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('containers.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $containerId = $this->route('container')->id;

        return [
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'ship_id' => ['nullable', 'integer'],
            'bl_number' => ['required', 'string', 'max:255'],
            'container_number' => ['required', 'string', 'max:255', Rule::unique('containers')->ignore($containerId)],
            'container_type' => ['nullable', 'string', 'max:255'],
            'status' => ['required', new \Illuminate\Validation\Rules\Enum(\App\Enums\ContainerStatus::class)],
            'departure_date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'video_review_arrival' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'total_shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'cost_allocation_method' => ['nullable', 'string', 'max:255'],

            'cars' => ['nullable', 'array'],
            'cars.*.id' => ['nullable', 'exists:cars,id'],
            'cars.*.name' => ['required_with:cars', 'string', 'max:255'],
            'cars.*.maker_id' => ['nullable', 'exists:makers,id'],
            'cars.*.car_model_id' => ['nullable', 'exists:car_models,id'],
            'cars.*.fuel_id' => ['nullable', 'exists:fuels,id'],
            'cars.*.images' => ['nullable', 'array'],
            'cars.*.images.*' => ['image', 'max:2048'],
            'cars.*.description' => ['nullable', 'string'],
            'cars.*.year' => ['nullable', 'integer'],
            'cars.*.color_id' => ['nullable', 'exists:colors,id'],
            'cars.*.body_number' => ['nullable', 'string', 'max:255'],
            'cars.*.engine_capacity_cc' => ['nullable', 'numeric'],
            'cars.*.registration_type' => ['nullable', 'string', 'max:255'],
            'cars.*.cif_price' => ['nullable', 'numeric', 'min:0'],
            'cars.*.transport_cost' => ['nullable', 'numeric', 'min:0'],
            'cars.*.expected_profit' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
