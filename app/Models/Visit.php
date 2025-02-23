<?php

namespace App\Models;

use App\Enums\ImagesTypeEnum;
use App\Enums\VisitTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visit extends Model
{
    protected $fillable = [
        'date',
        'time',
        'status',
        'comment',
        'employee_id',
        'store_id',
        'client_id',
        'rate',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
        'time' => 'datetime:H:i:s',
        'status' => VisitTypeEnum::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_visit');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id')
            ->where('role', 'client');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function imagesBeforeVisits(): HasMany
    {
        return $this->images()->where('type', ImagesTypeEnum::BEFORE);
    }

    public function imagesAfterVisits(): HasMany
    {
        return $this->images()->where('type', ImagesTypeEnum::AFTER);
    }

    public function imagesReportVisits(): HasMany
    {
        return $this->images()->where('type', ImagesTypeEnum::REPORTS);
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_visit');
    }
}
