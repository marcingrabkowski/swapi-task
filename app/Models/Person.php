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
        return $this->hasOne(Planet::class, 'external_id', 'homeworld');
    }

    public function getStarshipsAttribute() {
        $related = PersonStarship::where('person_external_id', $this->external_id)->with('starships')->get();

        $this->attributes['starships'] = [];

        foreach ($related as $record) {
            array_push($this->attributes['starships'],  $record->starships);
        }

        return $this->attributes['starships'];
    }
}
