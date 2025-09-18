<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'company',
        'country',
        'email_verification_token',
        'mfa_enabled',
        'mfa_secret',
        'status',
        'role',
        'job_title',
        'industry',
        'project_type',
        'project_scale',
        'project_location',
        'project_timeline',
        'latitude',
        'longitude',
        'formatted_address',
        'place_id',
        'contact_preference',
        'company_size',
        'years_in_business',
        'registration_number',
        'project_description',
        'last_login_at',
        'last_login_ip',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'mfa_secret',
    ];

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
            'mfa_enabled' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        // Super admin has all permissions
        if ($this->hasRole('super_admin')) {
            return true;
        }
        
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }
    
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
    
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }
    
    public function isClient(): bool
    {
        return $this->hasRole('client');
    }
    
    public function isAdmin(): bool
    {
        return $this->hasRole('super_admin') || $this->hasRole('admin') || $this->hasRole('content_manager') || $this->hasRole('finance') || $this->hasRole('guest');
    }
    
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }
    
    public function canManageUsers(): bool
    {
        return $this->hasAnyPermission(['users.create', 'users.update', 'users.delete']);
    }
    
    public function canManageRoles(): bool
    {
        return $this->hasAnyPermission(['roles.create', 'roles.update', 'roles.delete']);
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function activeSessions()
    {
        return $this->sessions()->where('last_activity', '>', now()->subMinutes(30));
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo ? asset('storage/' . $this->profile_photo) : null;
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }
}
