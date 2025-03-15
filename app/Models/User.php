<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'image',
        'address',
        'company_name',
        'store_numbers',
        'image',
        'phone',
    ];
    protected $appends = ['image_url'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];


    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeEmployees($query)
    {
        return $query->where('role', 'employee');
    }

    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('storage/default.png');
    }


    public function stores(): HasMany
    {
        return $this->hasMany(Store::class, 'client_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'client_id');
    }

    public function activeContract(): HasOne
    {
        return $this->hasOne(Contract::class, 'client_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->handleActiveContract();
        });

        static::updating(function ($user) {
            $user->handleActiveContract();
        });
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'client_id');
    }

    public function  visitsWithClient(): HasMany
    {
        return $this->hasMany(Visit::class , 'client_id')->with('client');
    }
}
