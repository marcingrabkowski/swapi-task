<?php

namespace App\Http\Controllers;

use App\Models\Starship;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class StarshipController extends Controller
{
    public function search(string $query) :JsonResponse {
        $starships = Starship::query();

        $starships = $starships->where('name', 'like', '%'.$query.'%');

        return Response::json([
            'data' => $starships->get()
        ], 201);
    }
}
