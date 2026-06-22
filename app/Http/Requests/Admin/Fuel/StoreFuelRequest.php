<?php

namespace App\Http\Requests\Admin\Fuel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFuelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('fuels.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'code'        => [
                'required', 
                'string', 
                'max:255', 
                \Illuminate\Validation\Rule::unique('fuels', 'code')->where(function ($query) {
                    return $query->where('company_id', auth()->user()->company_id);
                })
            ],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ];
    }
}
