<?php

namespace App\Http\Requests\Admin\CarModel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCarModelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('car_models.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'maker_id'  => ['required', 'exists:makers,id'],
            'name'      => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
