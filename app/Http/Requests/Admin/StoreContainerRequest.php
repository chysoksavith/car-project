<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContainerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('containers.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'ship_id' => ['nullable', 'string', 'max:255'],
            'bl_number' => ['required', 'string', 'max:255'],
            'container_number' => ['required', 'string', 'max:255', 'unique:containers,container_number'],
            'status' => ['required', Rule::enum(\App\Enums\ContainerStatus::class)],
            'departure_date' => ['nullable', 'date'],
            'expected_date' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
            'total_shipping_cost' => ['nullable', 'numeric', 'min:0'],

            'cars' => ['nullable', 'array'],
            'cars.*.id' => ['nullable', 'exists:cars,id'],
            'cars.*.name' => ['required_with:cars', 'string', 'max:255'],
            'cars.*.maker_id' => ['nullable', 'exists:makers,id'],
            'cars.*.car_model_id' => ['nullable', 'exists:car_models,id'],
            'cars.*.fuel_id' => ['nullable', 'exists:fuels,id'],
            'cars.*.images' => ['nullable', 'array'],
            'cars.*.images.*' => ['image', 'max:2048'],
            'cars.*.description' => ['nullable', 'string'],
            'cars.*.options' => ['nullable', 'string'],
            'cars.*.year' => ['nullable', 'integer'],
            'cars.*.color_id' => ['nullable', 'exists:colors,id'],
            'cars.*.body_number' => ['nullable', 'string', 'max:255', 'distinct', 'unique:cars,body_number'],
            'cars.*.engine_capacity_cc' => ['nullable', 'numeric'],
            'cars.*.registration_type' => ['nullable', Rule::enum(\App\Enums\RegistrationType::class)],
            'cars.*.cif_price' => ['nullable', 'numeric', 'min:0'],
            'cars.*.transport_cost' => ['nullable', 'numeric', 'min:0'],
            'cars.*.expected_profit' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'cars.*.body_number.distinct' => 'This body number is duplicated within the container.',
            'cars.*.body_number.unique' => 'This body number has already been taken.',
        ];
    }

    public function attributes(): array
    {
        return [
            'cars.*.name' => 'car name',
            'cars.*.maker_id' => 'maker',
            'cars.*.car_model_id' => 'car model',
            'cars.*.fuel_id' => 'fuel',
            'cars.*.year' => 'year',
            'cars.*.color_id' => 'color',
            'cars.*.body_number' => 'body number',
            'cars.*.engine_capacity_cc' => 'engine capacity',
            'cars.*.registration_type' => 'registration type',
            'cars.*.cif_price' => 'CIF price',
            'cars.*.transport_cost' => 'transport cost',
            'cars.*.expected_profit' => 'expected profit',
            'cars.*.description' => 'description',
            'cars.*.options' => 'options',
            'cars.*.images' => 'images',
        ];
    }
}
