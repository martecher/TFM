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

// ********  Comienza Rutas de Login   ******** 

Route::post('login', 'App\Http\Controllers\AuthController@login');

//  ******** Fin Rutas de Login   ******** 

// ********  Comienza Rutas de Categorias   ******** 
Route::apiResource('categoriasHabilidades', 'App\Http\Controllers\CategoriaHabilidadController',['except'=>['edit','create'] ])->middleware( 'auth:sanctum');

// ********  Comienza Rutas de Categorias   ******** 

//  ******** Comienza Rutas  de Usuarios  ******** 
Route::post('usuarios', 'App\Http\Controllers\UsuarioController@store');
Route::get('usuarios', 'App\Http\Controllers\UsuarioController@index')->middleware( 'auth:sanctum');
Route::put('usuarios/{id}', 'App\Http\Controllers\UsuarioController@update')->middleware( 'auth:sanctum');
Route::get('usuarios/{id}', 'App\Http\Controllers\UsuarioController@show')->middleware( 'auth:sanctum');
//  ********  Fin Rutas  de Usuarios  ******** 



//Route::apiResource('usuarios', 'App\Http\Controllers\UsuarioController',['except'=>['edit','create'] ])->middleware( 'auth:sanctum');




//  ******** Comienza Rutas de habilidades  ******** 

Route::apiResource('habilidades', 'App\Http\Controllers\HabilidadController',['except'=>['edit','create'] ])->middleware( 'auth:sanctum');

//  ******** Fin Rutas de habilidades  ******** 


//Route::apiResource('actividadesRealizadas', 'App\Http\Controllers\ActividadesRealizadasController',['except'=>['edit','create'] ])->middleware( 'auth:sanctum');


//  ******** Comienza Rutas de Actividades  ******** 

Route::get('actividadesRealizadas', 'App\Http\Controllers\ActividadesRealizadasController@index')->middleware( 'auth:sanctum');

Route::put('actividadesRealizadas/{id}', 'App\Http\Controllers\ActividadesRealizadasController@update');

Route::get('actividadesRealizadas/{id}', 'App\Http\Controllers\ActividadesRealizadasController@show')->middleware( 'auth:sanctum');

Route::get('actividadesRealizadas/asignadas/{valor}', 'App\Http\Controllers\ActividadesRealizadasController@asignadas')->middleware( 'auth:sanctum');

Route::get('actividadesRealizadas/asignadasUsuario/{id}', 'App\Http\Controllers\ActividadesRealizadasController@asignadasUsuario')->middleware( 'auth:sanctum');

Route::get('actividadesRealizadas/solicitadasUsuario/{id}', 'App\Http\Controllers\ActividadesRealizadasController@solicitadasUsuario')->middleware( 'auth:sanctum');

Route::get('actividadesRealizadas/finalizadas/{valor}', 'App\Http\Controllers\ActividadesRealizadasController@finalizadas')->middleware( 'auth:sanctum');

Route::get('actividadesRealizadas/actividadesEnRealizacion/{id}', 'App\Http\Controllers\ActividadesRealizadasController@enRealizacionUsuario')->middleware( 'auth:sanctum');

Route::get('actividadesRealizadas/actividadesEnSolicitud/{id}', 'App\Http\Controllers\ActividadesRealizadasController@enSolicitudUsuario')->middleware( 'auth:sanctum');

Route::get('actividadesRealizadas/actividadesTerminadas/{id}', 'App\Http\Controllers\ActividadesRealizadasController@enTerminadasUsuario')->middleware( 'auth:sanctum');

Route::get('actividadesRealizadas/solicitadasPorUsuario/{id}', 'App\Http\Controllers\ActividadesRealizadasController@solicitadasPorUsuario')->middleware( 'auth:sanctum');

Route::get('actividadesRealizadas/realizadasPorUsuario/{id}', 'App\Http\Controllers\ActividadesRealizadasController@realizadasPorUsuario')->middleware( 'auth:sanctum');

Route::get('actividadesRealizadas/actividadNombre/{nombre}/finalizada/{$valor}', 'App\Http\Controllers\ActividadesRealizadasController@actividadNombre')->middleware( 'auth:sanctum');

//  ******** Fin Rutas de Actividades  ******** 



//  ******** Comienza Rutas de Mensajes  ******** 

Route::get('mensajesTarea/tarea/{id}', 'App\Http\Controllers\MensajeController@mensajesDeLaTarea')->middleware( 'auth:sanctum');
Route::post('mensajes', 'App\Http\Controllers\MensajeController@store')->middleware( 'auth:sanctum');
Route::put('mensajes/tarea/marcarleidos/{id}', 'App\Http\Controllers\MensajeController@mensajesDeLaTarea')->middleware( 'auth:sanctum');


 

//  ******** Fin Rutas de Mensajes  ******** 

