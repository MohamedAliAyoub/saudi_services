<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends User
{
    protected $table = 'users';
    protected $attributes = ['type' => 'employee'];
}
