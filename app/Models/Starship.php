<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Starship extends Model
{
    protected $table = 'starships';

    protected $hidden = ['id', 'created_at', 'updated_at'];
}
