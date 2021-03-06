<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/迷雾', 'Tank\PlayerController@init');
Route::post('/init', 'Tank\PlayerController@init');
Route::post('/player/init', 'Tank\PlayerController@init');

Route::post('/player/action', 'Tank\PlayerController@action');