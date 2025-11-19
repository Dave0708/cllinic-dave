<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // --- Database Configuration (From Friend's File) ---
    // These are necessary because the table uses 'user_id' instead of 'id'
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     * I combined both files here so you can use Name, Email, Username, or Contact.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',      // Standard Laravel
        'email',     // Standard Laravel
        'username',  // Custom
        'contact',   // Custom
        'password',
        'role',      // Custom (1=Admin, 2=Staff, 3=User)
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
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ==========================================
    //      CUSTOM RELATIONSHIPS & METHODS
    // ==========================================

    /**
     * Get the role information for the user.
     * This assumes you have a 'Role' model and a 'roles' table.
     */
    public function roleInfo()
    {
        // This links the 'role' column in users table to 'id' in roles table
        return $this->belongsTo(Role::class, 'role', 'id');
    }

    /**
     * Check if user is admin (Role ID: 1)
     */
    public function isAdmin()
    {
        return $this->role === 1;
    }

    /**
     * Check if user is staff (Role ID: 2)
     */
    public function isStaff()
    {
        return $this->role === 2;
    }

    /**
     * Check if user is regular user (Role ID: 3)
     */
    public function isUser()
    {
        return $this->role === 3;
    }

    /**
     * Get the name of the unique identifier for the user.
     * Required because we changed primaryKey to 'user_id'.
     */
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    /**
     * Get the route key for the model.
     * Required for route binding (e.g. /users/{user}).
     */
    public function getRouteKeyName()
    {
        return 'user_id';
    }
}