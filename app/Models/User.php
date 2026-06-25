<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\HasTenant;
use App\Models\Traits\TracksAuditCols;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['company_id', 'department_id', 'name', 'first_name', 'last_name', 'national_id', 'phone_number', 'birth_date', 'user_type', 'is_active', 'email', 'password', 'created_by', 'updated_by', 'deleted_by'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasTenant, SoftDeletes, TracksAuditCols; 
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'birth_date' => 'date',
            'user_type' => \App\Enums\UserType::class,
        ];
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
