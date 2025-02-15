<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends User
{
    protected $table = 'users';
    protected $attributes = ['type' => 'client'];


}
