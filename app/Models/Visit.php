<?php

namespace App\Models;

use App\Enums\ImagesTypeEnum;
use App\Enums\VisitTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Visit extends Model implements HasMedia
{
    use InteractsWithMedia;
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
        'image' => 'array',
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('visit_images')
            ->useDisk('public')
            ->useFallbackUrl('default.png')
            ->useFallbackPath(public_path('default.png'))
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);
    }

    public function getMediaDirectory(): string
    {
        return 'visits/' . $this->id;
    }

    public function getImageUrls(): array
    {
        return $this->getMedia('visit_images')->map(function (Media $media) {
            return $media->getUrl();
        })->toArray();
    }

}
