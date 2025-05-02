<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Translatable\HasTranslations;

class Client extends User
{
    use  HasTranslations;
    protected $table = 'users';
    protected $attributes = ['role' => 'client'];
    public array $translatable = ['name'];

    protected $casts= [
        'name' => 'array',
    ];


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
        return $this->hasOne(Contract::class)->latest();
    }

    public function storesOfContract(): HasManyThrough
    {
        return $this->hasManyThrough(Store::class, Contract::class, 'client_id', 'contract_id')->with('visits');
    }


    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'client_id');
    }


}
