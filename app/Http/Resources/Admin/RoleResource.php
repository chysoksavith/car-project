<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'permissions_count' => $this->permissions_count ?? $this->permissions->count(),
            'permissions'      => PermissionResource::collection(
                $this->whenLoaded('permissions')
            ),
        ];
    }
}
