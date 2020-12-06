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
//Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//ruta funcionando sin proteger
// Route::resource('categoriasHabilidades','App\Http\Controllers\CategoriaHabilidadController');

Route::post('login', 'App\Http\Controllers\AuthController@login');

Route::apiResource('categoriasHabilidades', 'App\Http\Controllers\CategoriaHabilidadController',['except'=>['edit','create'] ])->middleware( 'auth:sanctum');


Route::post('usuarios', 'App\Http\Controllers\UsuarioController@store');
Route::get('usuarios', 'App\Http\Controllers\UsuarioController@index')->middleware( 'auth:sanctum');


//Route::apiResource('usuarios', 'App\Http\Controllers\UsuarioController',['except'=>['edit','create'] ])->middleware( 'auth:sanctum');

Route::apiResource('habilidades', 'App\Http\Controllers\HabilidadController',['except'=>['edit','create'] ])->middleware( 'auth:sanctum');

Route::apiResource('actividadesRealizadas', 'App\Http\Controllers\ActividadesRealizadasController',['except'=>['edit','create'] ])->middleware( 'auth:sanctum');