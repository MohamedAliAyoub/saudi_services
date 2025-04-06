<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitStatusLog extends Model
{
    use HasFactory;
    protected  $fillable = [
        'visit_id',
        'user_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
