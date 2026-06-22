<?php

namespace App\Http\Requests\Admin\Department;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('departments.edit');
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
                \Illuminate\Validation\Rule::unique('departments', 'code')
                    ->ignore($this->route('department'))
                    ->where(function ($query) {
                        return $query->where('company_id', auth()->user()->company_id);
                    })
            ],
            'is_active'   => ['boolean'],
        ];
    }
}
