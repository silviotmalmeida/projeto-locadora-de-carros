<?php

use App\Http\Controllers\BrandController;
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



// definindo as rotas de Brand
Route::apiResource('brand', 'App\Http\Controllers\BrandController')->middleware('jwt.auth');

// definindo as rotas de Type
Route::apiResource('type', 'App\Http\Controllers\TypeController');

// definindo as rotas de Car
Route::apiResource('car', 'App\Http\Controllers\CarController');

// definindo as rotas de Client
Route::apiResource('client', 'App\Http\Controllers\ClientController');

// definindo as rotas de Location
Route::apiResource('location', 'App\Http\Controllers\LocationController');

// definindo as rotas de autenticação
Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('logout', 'App\Http\Controllers\AuthController@logout');
Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
Route::post('me', 'App\Http\Controllers\AuthController@me');
