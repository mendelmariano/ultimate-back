<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\UserController;
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

Route::post('/authenticator', 'App\Http\Controllers\AuthenticationApi@login');
Route::post('/user', 'App\Http\Controllers\AuthenticationApi@register');

Route::middleware([])->group(function () {
    Route::get('futdb/index', 'App\Http\Controllers\FutDBController@index');
});

Route::middleware(['auth:sanctum'])->group(function(){


    Route::get('/user', 'App\Http\Controllers\AuthenticationApi@loadUserWithClubs');
    Route::get('/users', 'App\Http\Controllers\UserController@index');
    Route::get('/users/{user}', 'App\Http\Controllers\UserController@show');
    Route::post('/users/roles/attach', 'App\Http\Controllers\RoleController@attachRole');
    Route::post('/users/roles/detach', 'App\Http\Controllers\RoleController@detachRole');
    Route::get('/users/roles/logged/show', 'App\Http\Controllers\RoleController@showRolesUserLogged');
    Route::get('/users/roles/show/{id}', 'App\Http\Controllers\RoleController@showRolesForUser');

    Route::group(['middleware' => ['role_or_permission: admin']], function () {
        Route::delete('/user/{user}', 'App\Http\Controllers\AuthenticationApi@destroy');
    });





    Route::post('/logout', 'App\Http\Controllers\AuthenticationApi@logout');
    Route::apiResource('clubs',  ClubController::class);
    Route::post('/results/computa_resultado', 'App\Http\Controllers\ResultController@computaResultado');
    Route::patch('/results/confirma_resultado/{id}', 'App\Http\Controllers\ResultController@confirmaResultado');
    Route::patch('/results/rejeita_resultado/{id}', 'App\Http\Controllers\ResultController@rejeitaResultado');
    Route::apiResource('results',  ResultController::class);

});





