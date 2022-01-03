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
Route::post('/club', 'App\Http\Controllers\AuthenticationApi@registerClub');

Route::middleware([])->group(function () {
    Route::get('futdb/index', 'App\Http\Controllers\FutDBController@index');
});

Route::middleware(['jwt.auth'])->group(function(){


    Route::get('/user', 'App\Http\Controllers\AuthenticationApi@loadUserWithClubs');
    Route::apiResource('users',  UserController::class);
    Route::post('/users/roles/attach', 'App\Http\Controllers\RoleController@attachRole');
    Route::post('/users/roles/detach', 'App\Http\Controllers\RoleController@detachRole');
    Route::get('/users/roles/logged/show', 'App\Http\Controllers\RoleController@showRolesUserLogged');
    Route::get('/users/roles/show/{user}', 'App\Http\Controllers\RoleController@showRolesForUser');

    Route::group(['middleware' => ['role_or_permission: admin']], function () {
        Route::delete('/user/{user}', 'App\Http\Controllers\AuthenticationApi@destroy');
    });





    Route::post('/logout', 'App\Http\Controllers\AuthenticationApi@logout');

    Route::apiResource('clubs',  ClubController::class);
    Route::post('/clubs/result_post', 'App\Http\Controllers\ClubController@indexForPost');
    Route::get('/users/club/{user}', 'App\Http\Controllers\UserController@clubsForUser');
    Route::post('/results/computa_resultado', 'App\Http\Controllers\ResultController@computaResultado');
    Route::patch('/results/computa_resultados', 'App\Http\Controllers\ResultController@computaResultados');
    Route::patch('/results/confirma_resultado/{id}', 'App\Http\Controllers\ResultController@confirmaResultado');
    Route::patch('/results/rejeita_resultado/{id}', 'App\Http\Controllers\ResultController@rejeitaResultado');
    Route::apiResource('results',  ResultController::class);
    Route::get('/results/users/confirm_user', 'App\Http\Controllers\ResultController@forUserConfirm');
    Route::get('/results/users/confirmed_user', 'App\Http\Controllers\ResultController@forUserConfirmed');

});





