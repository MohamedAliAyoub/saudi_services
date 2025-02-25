<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Image extends Model
{
    use HasFactory , InteractsWithMedia;

    protected $fillable = [
        'path',
        'visit_id',
        'type',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function getFullPathAttribute()
    {
        return asset(   'uploads/images/' . $this->path);
    }


}
