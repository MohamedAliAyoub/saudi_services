<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Symfony\Component\String\u;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PartnerServise extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'service_id',
    ];


    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
