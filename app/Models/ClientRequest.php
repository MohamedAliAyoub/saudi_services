<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClientRequest extends Model
{
    protected $fillable = [
        'client_id',
        'store_id',
        'visit_id',
        'status',
        'comment',
        'date',
        'time',
    ];

    protected static function booted()
    {
        static::creating(function ($clientRequest) {
            $clientRequest->client_id = auth()->id();
            $clientRequest->status = 'pending';
        });

        static::updating(function ($clientRequest) {
            if (is_null($clientRequest->client_id)) {
                $clientRequest->client_id = auth()->id();
            }
            if (is_null($clientRequest->status)) {
                $clientRequest->status = 'pending';
            }
        });
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class , 'client_request_services' ,);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class)->where('role', 'client');
    }
}
