<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'role',
        'must_change_password'
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
            'role' => UserRole::class,
            'must_change_password' => 'boolean',
        ];
    }

    public function studentParent()
    {
        return $this->hasOne(StudentParent::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isTuStaff(): bool
    {
        return $this->role === UserRole::TU_STAFF;
    }

    public function isParent(): bool
    {
        return $this->role === UserRole::PARENT;
    }

    public function getInitialsAttribute(): string
    {
        return \Illuminate\Support\Str::of($this->name)
            ->explode(' ')
            ->map(fn($word) => $word[0] ?? '')
            ->take(2)
            ->join('');
    }
}
