<?php

namespace App\Http\Requests\Admin\InspectionItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('inspection_items.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'parent_id'     => ['nullable', 'exists:inspection_items,id'],
            'name_kh'       => ['required', 'string', 'max:255'],
            'name_en'       => ['nullable', 'string', 'max:255'],
            'default_price' => ['nullable', 'numeric', 'min:0'],
            'is_active'     => ['boolean'],
        ];
    }
}
