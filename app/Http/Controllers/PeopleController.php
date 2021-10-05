<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class PeopleController extends Controller
{
    public function search(string $query) :JsonResponse {
        $person = Person::query();

        $person = $person->where('name', 'like', '%'.$query.'%');

        $person = $person->with('homeworld');

        return Response::json([
            'data' => $person->get()
        ], 201);
    }
}
