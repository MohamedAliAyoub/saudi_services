<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;


class Admin extends User
{
    protected $table = 'users';
    protected $attributes = ['role' => 'admin'];

    protected static function booted()
    {
        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->where('role', 'admin');
        });
    }
}
