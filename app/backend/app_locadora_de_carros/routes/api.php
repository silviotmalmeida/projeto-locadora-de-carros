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

// agrupando as rotas da aplicação que serão protegidas por autenticação
// foi criado o prefixo v1 para possibilitar versionamento da api
Route::prefix('v1')->middleware('jwt.auth')->group(function () {

    // definindo as rotas de Brand
    Route::apiResource('brand', 'App\Http\Controllers\BrandController');

    // definindo as rotas de Type
    Route::apiResource('type', 'App\Http\Controllers\TypeController');

    // definindo as rotas de Car
    Route::apiResource('car', 'App\Http\Controllers\CarController');

    // definindo as rotas de Client
    Route::apiResource('client', 'App\Http\Controllers\ClientController');

    // definindo as rotas de Location
    Route::apiResource('location', 'App\Http\Controllers\LocationController');

    // definindo a rota de detalhe do usuário autenticado
    Route::post('me', 'App\Http\Controllers\AuthController@me');

    // definindo a rota de refresh do token de autorização
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');

    // definindo a rota de logout e expiração do token
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
});

// definindo as rotas de autenticação
Route::post('login', 'App\Http\Controllers\AuthController@login');



