<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planet extends Model
{
    protected $table = 'planets';

    protected $hidden = ['id', 'created_at', 'updated_at'];
}
