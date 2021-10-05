<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonStarship extends Model
{
    protected $table = 'people_starships';

    public function starships()
    {
        return $this->hasMany(Starship::class, 'id', 'starship_id');
    }
}
