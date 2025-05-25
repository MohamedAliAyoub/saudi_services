<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Contract extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $with = ['services'];
    protected $fillable = [
        'store_numbers',
        'visits_number',
        'contract_create_date',
        'contract_end_date',
        'client_id',
        'status',
        'pdf_path'
    ];


    protected $appends = ['name'];


    public function getNameAttribute(): string
    {
        $clientName = $this->client->translated_name ?? 'Unknown Client';

        return "Contract #{$this->id} - {$clientName} (" .
            (($this->contract_create_date instanceof \DateTime) ?
                $this->contract_create_date->format('d/m/Y') :
                ($this->contract_create_date ? \Carbon\Carbon::parse($this->contract_create_date)->format('d/m/Y') : 'No start date')) .
            " to " .
            (($this->contract_end_date instanceof \DateTime) ?
                $this->contract_end_date->format('d/m/Y') :
                ($this->contract_end_date ? \Carbon\Carbon::parse($this->contract_end_date)->format('d/m/Y') : 'No end date')) .
            ")";
    }
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
    public function visits(): HasManyThrough
    {
        return $this->hasManyThrough(Visit::class, Store::class);
    }


    public function getPdfUrlAttribute(): string
    {
        $media = $this->getFirstMedia('contract_pdfs');
        return $media ? $media->getUrl() : '';
    }

}
