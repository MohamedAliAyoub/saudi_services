<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
class Client extends User
{
    protected $table = 'users';
    protected $attributes = ['role' => 'client'];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class , 'client_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'client_id');
    }

    public function activeContract(): HasOne
    {
        return $this->hasOne(Contract::class, 'client_id');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'client_id');
    }



}
