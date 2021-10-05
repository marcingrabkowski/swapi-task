<?php

namespace App\Services;

use App\Models\Person;
use App\Models\PersonStarship;
use App\Models\Planet;
use App\Models\Starship;

class CaptureDataService {

    private $url = 'https://swapi.dev/api/';

    public function savePeople(array $people, ?int $group = null) {
        if(!$group) {
            $personRecord = Person::where('name', $people['name'])->first();

            if (!$personRecord) {
                $personRecord = new Person();
                $personRecord->external_id = $this->getIdFromUrl($personRecord['url']);
            }

            if(!$this->getHomeworldByUrl($people['homeworld'])) {
                return false;
            }

            $personRecord->name = $people['name'];

            $personRecord->homeworld = $this->getHomeworldByUrl($people['homeworld']);

            $personRecord->height = $people['height'];
            $personRecord->mass = $people['mass'];
            $personRecord->hair_color = $people['hair_color'];
            $personRecord->gender = $people['gender'];

            $personRecord->save();

            foreach($people['starships'] as $starship) {
                $this->savePersonStarships($starship, $personRecord->external_id);
            }
        }

        if($group) {
            foreach ($people['results'] as $key => $person) {
                $personRecord = Person::where('name', $person['name'])->first();

                if(!$this->getHomeworldByUrl($person['homeworld'])) {
                    return false;
                }

                if (!$personRecord) {
                    $personRecord = new Person();
                    $personRecord->external_id = $this->getIdFromUrl($person['url']);
                }

                $personRecord->name = $person['name'];

                $personRecord->homeworld = $this->getHomeworldByUrl($person['homeworld']);

                $personRecord->height = $person['height'];
                $personRecord->mass = $person['mass'];
                $personRecord->hair_color = $person['hair_color'];
                $personRecord->gender = $person['gender'];

                $personRecord->save();

                foreach($person['starships'] as $starship) {
                    $this->savePersonStarships($starship, $personRecord->external_id);
                }
            }

            if(array_key_exists('next', $people) && $people['next']) {
                $nextPage = $this->request($people['next'], null, 'GET', true);
                $this->savePeople($nextPage, true);
            }
        }

        return true;
    }

    public function saveStarships(array $starships, ?int $group = null) :void {
        if(!$group) {
            $starshipRecord = Starship::where('name', $starships['name'])->first();

            if (!$starshipRecord) {
                $starshipRecord = new Starship();
                $starshipRecord->external_id = $this->getIdFromUrl($starshipRecord['url']);
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
                    $starshipRecord->external_id = $this->getIdFromUrl($starship['url']);
                }

                $starshipRecord->name = $starship['name'];
                $starshipRecord->model = $starship['model'];

                $starshipRecord->save();
            }

            if(array_key_exists('next', $starships) && $starships['next']) {
                $nextPage = $this->request($starships['next'], null, 'GET', true);
                $this->saveStarships($nextPage, true);
            }
        }
    }

    public function savePlanets(array $planets, ?int $group = null) :void {

        if(!$group) {
            $planetRecord = Planet::where('name', $planets['name'])->first();

            if (!$planetRecord) {
                $planetRecord = new Planet();
                $planetRecord->external_id = $this->getIdFromUrl($planetRecord['url']);
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
                    $planetRecord->external_id = $this->getIdFromUrl($planet['url']);
                }

                $planetRecord->name = $planet['name'];
                $planetRecord->terrain = $planet['terrain'];

                $planetRecord->save();
            }


            if(array_key_exists('next', $planets) && $planets['next']) {
                $nextPage = $this->request($planets['next'], null, 'GET', true);
                $this->savePlanets($nextPage, true);
            }
        }
    }

    public function getIdFromUrl($url) {
        return (int) filter_var($url, FILTER_SANITIZE_NUMBER_INT);
    }

    public function getHomeworldByUrl (string $url) :?int {
        $homeworld = $this->getIdFromUrl($url);
        $planet = Planet::where('external_id', $homeworld)->first();

        if($planet) {
            return $planet->id;
        }

        return false;
    }

    public function savePersonStarships (string $url, int $id) :void {
        $starship = $this->getIdFromUrl($url);

        $starshipRecord  = Starship::where('external_id', $starship)->first();

        if(!$starshipRecord) {
            return;
        }

        $isRelationExists = PersonStarship::where('person_external_id', $id)->where('starship_external_id', $starshipRecord->external_id)->first();

        if(!$isRelationExists) {
            $relation = new PersonStarship();

            $relation->person_external_id = $id;
            $relation->starship_external_id = $starshipRecord->external_id;
            $relation->save();
        }
    }

    public function request($path, $id = null, $method = 'GET', $fulladdress = false) :?array {

        $endpoint = $this->url.$path.'/'.$id;

        if($fulladdress) {
            $endpoint = $path;
        }

        echo 'Current url: '.$endpoint.PHP_EOL;

        $client = new \GuzzleHttp\Client();

        $response = $client->request($method, $endpoint);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        return json_decode($response->getBody(), true);
    }
}
