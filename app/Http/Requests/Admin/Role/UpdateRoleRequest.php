<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('roles.edit');
    }

    public function rules(): array
    {
        $roleId = $this->route('role')?->id;

        return [
            'name'          => ['required', 'string', 'max:100', "unique:roles,name,{$roleId}"],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A role with this name already exists.',
        ];
    }
}
