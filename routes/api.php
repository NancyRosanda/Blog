<?php

use Illuminate\Http\Request;

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

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');


Route::prefix('posts')->group(function () {
    $p = 'PostController@';

    Route::middleware('logged-in')->group(function () use ($p) {

    Route::post('{post}/comments', $p . 'storeComment');
    Route::get('{post}/comments', $p . 'comments');
    Route::get('', $p . 'index');

    Route::post('create', $p . 'store');
    Route::post('{post}/update', $p . 'update');
    Route::delete('{post}', $p . 'delete');
});
    Route::get('show', $p . 'show');
});
