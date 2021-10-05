<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/people/search/{query}', [\App\Http\Controllers\PeopleController::class, 'search']);
Route::get('/starships/search/{query}', [\App\Http\Controllers\StarshipController::class, 'search']);
Route::get('/planets/search/{query}', [\App\Http\Controllers\PlanetController::class, 'search']);
