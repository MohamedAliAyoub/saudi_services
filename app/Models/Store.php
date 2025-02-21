<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'client_id',
        'status',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id')
            ->where('role', 'client');
    }
}
