<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

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
