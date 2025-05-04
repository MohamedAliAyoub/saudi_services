<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $with = ['services'];
    protected $fillable = [
        'store_numbers',
        'visits_number',
        'contract_create_date',
        'contract_end_date',
        'client_id',
        'status',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'contract_visits');
    }


    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }


}
