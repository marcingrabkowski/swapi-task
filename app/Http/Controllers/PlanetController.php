<?php

namespace App\Http\Controllers;

use App\Models\Planet;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class PlanetController extends Controller
{
    public function search(string $query) :JsonResponse {
        $planets = Planet::query();

        $planets = $planets->where('name', 'like', '%'.$query.'%');

        return Response::json([
            'data' => $planets->get()
        ], 201);
    }
}
