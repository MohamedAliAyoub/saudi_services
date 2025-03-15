<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'client_id',
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

    public function visits():HasMany
    {
        return $this->hasMany(Visit::class , 'store_id');
    }

    public function visitsWithClient(): HasMany
    {
        return $this->hasMany(Visit::class , 'store_id')->with('client');
    }
}
