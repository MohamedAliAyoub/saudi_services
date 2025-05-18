<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Employee extends User
{
    protected $table = 'users';

    // Apply global scope to only include employees
    protected static function booted()
    {
        static::addGlobalScope('employee', function (Builder $builder) {
            $builder->where('role', 'employee');
        });
    }

    // Ensure new employees are created with the correct role
    protected $attributes = [
        'role' => 'employee'
    ];
}
