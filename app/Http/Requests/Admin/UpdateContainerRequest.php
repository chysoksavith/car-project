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
        ];
    }
}
