<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'client_id',
        'contract_id',
        'visits_number',
        'status',
    ];

    protected $casts = [
        'name' => 'array',
    ];
    protected $appends = ['translated_name'];


//    public function client(): BelongsTo
//    {
//        return $this->belongsTo(User::class, 'client_id')
//            ->where('role', 'client');
//    }



    public function contracts(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function activeContractStores(): HasManyThrough
    {
        return $this->hasManyThrough(
            Store::class,
            Contract::class,
            'client_id', // Foreign key on the contracts table
            'contract_id', // Foreign key on the stores table
            'id', // Local key on the clients table
            'id' // Local key on the contracts table
        )->where('contracts.status', 'active');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'store_id');
    }

    public function visitsWithClient(): HasMany
    {
        return $this->hasMany(Visit::class, 'store_id')->with('client');
    }

    public function getTranslatedNameAttribute()
    {
        if (is_array($this->name))
            return $this->name[app()->getLocale()] ?? $this->name['ar'];
        else
            return $this->name;
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
    public function clientThroughContract(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Contract::class,
            'id',
            'id',
            'contract_id',
            'client_id'
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            if (!is_null($store->contract_id)) {
                $contract = Contract::find($store->contract_id);
                if ($contract) {
                    $store->client_id = $contract->client_id;
                }
            }
        });
        static::updating(function ($store) {
//            dd($store);
            if (!is_null($store->contract_id)) {
                $contract = Contract::find($store->contract_id);
                if ($contract) {
                    $store->client_id = $contract->client_id;
                }
            }
        });
    }


}
