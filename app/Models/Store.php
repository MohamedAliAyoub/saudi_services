<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\HasTranslations;

class Store extends Model
{
    use HasFactory ;

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

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id')
            ->where('role', 'client');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class, 'store_id');
    }

    public function visitsWithClient(): HasMany
    {
        return $this->hasMany(Visit::class, 'store_id')->with('client');
    }


    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
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
