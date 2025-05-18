<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
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
    protected $appends = ['image_url' , 'translated_name'];



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
        'password' => 'hashed',
        'name' => 'array'
    ];


    public function getTranslatedNameAttribute()
    {
        if (is_array($this->name))
            return  $this->name['ar'];
        else
            return $this->name;
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

   public function getNameAttribute($value)
   {
       // If name is already an array (Laravel decoded JSON)
       if (is_array($value)) {
           return $value['ar'] ?? $value;
       }

       // If name is stored as JSON string
       if (is_string($value) && $this->isJson($value)) {
           $decoded = json_decode($value, true);
           return $decoded['ar'] ?? $value;
       }

       // Otherwise return as is
       return $value;
   }

   private function isJson($string) {
       if (!is_string($string)) return false;
       json_decode($string);
       return json_last_error() === JSON_ERROR_NONE;
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

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class, 'client_id');
    }


    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'client_id');
    }

    public function visitsWithClient(): HasMany
    {
        return $this->hasMany(Visit::class, 'client_id')->with('client');
    }


    public function activeContract(): HasOne
    {
        return $this->hasOne(Contract::class, 'client_id')->orderBy('id', 'desc')->where('status', 'active');
    }

    public function storesWithActiveContracts(): HasManyThrough
    {
        return $this->hasManyThrough(
            Contract::class,
            Store::class,
            'contract_id',
            'id',
            'contract_id',
            'id'
        )->where('contracts.status', 'active');
    }


    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }elseif ($panel->getId() === 'client') {
            return $this->role === 'client';
        }elseif ($panel->getId() === 'employee') {
            return $this->role === 'employee';
        }

        return true;

    }

}
