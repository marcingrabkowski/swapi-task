<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'people';

    protected $appends = array('starships');

    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function homeworld()
    {
        return $this->hasOne(Planet::class, 'id', 'homeworld');
    }

    public function getStarshipsAttribute() {
        $related = PersonStarship::where('person_id', $this->id)->with('starships')->get();

        $this->attributes['starships'] = [];
        foreach ($related as $record) {
            $this->attributes['starships'] = $record->starships;
        }


        return $this->attributes['starships'];
    }
}
