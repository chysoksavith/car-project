<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'birth_date'   => ['nullable', 'date'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'user_type'    => ['required', 'string', 'in:backend,frontend'],
            'is_active'    => ['boolean'],
            'roles'        => ['nullable', 'array'],
            'roles.*'      => ['string', 'exists:roles,name'],
            'address'      => ['nullable', 'array'],
            'company_id'   => ['nullable', 'exists:companies,id'],
        ];
    }
}
