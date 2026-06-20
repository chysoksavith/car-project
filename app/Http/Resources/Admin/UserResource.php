<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'first_name'   => $this->first_name,
            'last_name'    => $this->last_name,
            'email'        => $this->email,
            'phone_number' => $this->phone_number,
            'user_type'    => $this->user_type,
            'is_active'    => $this->is_active,
            'roles'        => $this->whenLoaded('roles', fn () => $this->roles->pluck('name')),
            'created_at'   => $this->created_at,
        ];
    }
}
