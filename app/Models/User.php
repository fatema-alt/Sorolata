<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\AdminRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'avatar_url',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'role' => AdminRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === AdminRole::SUPER_ADMIN;
    }

    public function isStaff(): bool
    {
        return in_array($this->role, [
            AdminRole::SUPER_ADMIN,
            AdminRole::MANAGER,
            AdminRole::CONTENT_EDITOR,
            AdminRole::SUPPORT,
        ], true);
    }

    public function hasRole(string|AdminRole $role): bool
    {
        $roleValue = $role instanceof AdminRole ? $role->value : $role;
        return ($this->role?->value ?? 'customer') === $roleValue;
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function customerNotes(): HasMany
    {
        return $this->hasMany(CustomerNote::class, 'customer_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function compares(): HasMany
    {
        return $this->hasMany(ProductCompare::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
