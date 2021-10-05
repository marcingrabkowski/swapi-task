<?php

namespace App\Services;

use App\Models\Person;
use App\Models\PersonStarship;
use App\Models\Planet;
use App\Models\Starship;

class CaptureDataService {

    private $url = 'https://swapi.dev/api/';

    public function savePeople(array $people, ?int $group = null) :void {
        if(!$group) {
            $personRecord = Person::where('name', $people['name'])->first();

            if (!$personRecord) {
                $personRecord = new Person();
            }

            $personRecord->name = $people['name'];

            $personRecord->homeworld = $this->getHomeworldByUrl($people['homeworld']);

            $personRecord->height = $people['height'];
            $personRecord->mass = $people['mass'];
            $personRecord->hair_color = $people['hair_color'];
            $personRecord->gender = $people['gender'];

            $personRecord->save();
            foreach($people['starships'] as $starship) {
                $this->savePersonStarshipsFromUrl($starship, $personRecord->id);
            }
        }

        if($group) {
            foreach ($people['results'] as $key => $person) {
                $personRecord = Person::where('name', $person['name'])->first();

                if (!$personRecord) {
                    $personRecord = new Person();
                }

                $personRecord->name = $person['name'];

                $personRecord->homeworld = $this->getHomeworldByUrl($person['homeworld']);

                $personRecord->height = $person['height'];
                $personRecord->mass = $person['mass'];
                $personRecord->hair_color = $person['hair_color'];
                $personRecord->gender = $person['gender'];

                $personRecord->save();

                foreach($person['starships'] as $starship) {
                    $this->savePersonStarshipsFromUrl($starship, $personRecord->id);
                }
            }

            if(array_key_exists('next', $people)) {
                $nextPage = $this->request($people['next'], null, 'GET', true);
                $this->savePeople($nextPage, true);
            }
        }
    }

    public function saveStarships(array $starships, ?int $group = null) :void {
        if(!$group) {
            $starshipRecord = Starship::where('name', $starships['name'])->first();

            if (!$starshipRecord) {
                $starshipRecord = new Starship();
            }

            $starshipRecord->name = $starships['name'];
            $starshipRecord->model = $starships['model'];

            $starshipRecord->save();
        }

        if($group) {
            foreach ($starships['results'] as $starship) {
                $starshipRecord = Starship::where('name', $starship['name'])->first();

                if (!$starshipRecord) {
                    $starshipRecord = new Starship();
                }

                $starshipRecord->name = $starship['name'];
                $starshipRecord->model = $starship['model'];

                $starshipRecord->save();
            }
        }
    }

    public function savePlanets(array $planets, ?int $group = null) :void {

        if(!$group) {
            $planetRecord = Planet::where('name', $planets['name'])->first();

            if (!$planetRecord) {
                $planetRecord = new Planet();
            }

            $planetRecord->name = $planets['name'];
            $planetRecord->terrain = $planets['terrain'];

            $planetRecord->save();
        }

        if($group) {
            foreach ($planets['results'] as $planet) {
                $planetRecord = Planet::where('name', $planet['name'])->first();

                if (!$planetRecord) {
                    $planetRecord = new Planet();
                }

                $planetRecord->name = $planet['name'];
                $planetRecord->terrain = $planet['terrain'];

                $planetRecord->save();
            }
        }
    }

    public function getHomeworldByUrl (string $url) :?int {
        $homeworld = $this->request($url, null, 'GET', true);
        $planet = Planet::where('name', $homeworld['name'])->first();

        if(!$planet) {
            $planet = new Planet();
        }

        $planet->name = $homeworld['name'];
        $planet->terrain = $homeworld['terrain'];

        $planet->save();

        return $planet->id;
    }

    public function savePersonStarshipsFromUrl (string $url, int $id) :void {
        $starship = $this->request($url, null, 'GET', true);

        $starshipRecord  = Starship::where('name', $starship['name'])->first();

        if(!$starshipRecord ) {
            $starshipRecord = new Starship();
            $starshipRecord->name = $starship['name'];
            $starshipRecord->model = $starship['model'];

            $starshipRecord->save();
        }

        $isRelationExists = PersonStarship::where('person_id', $id)->where('starship_id', $starshipRecord->id)->first();

        if(!$isRelationExists) {
            $relation = new PersonStarship();

            $relation->person_id = $id;
            $relation->starship_id = $starshipRecord->id;
            $relation->save();
        }
    }

    public function request($path, $id = null, $method = 'GET', $fulladdress = false) :?array {

        $endpoint = $this->url.$path.'/'.$id;

        if($fulladdress) {
            $endpoint = $path;
        }

        $client = new \GuzzleHttp\Client();

        $response = $client->request($method, $endpoint);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        return json_decode($response->getBody(), true);
    }
}
