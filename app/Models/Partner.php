<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Partner extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'logo',
        'date_from',
        'date_to',
        'link',
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'partner_services');
    }
}
