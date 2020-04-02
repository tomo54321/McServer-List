<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', "ServerController@index")->name("home");
Route::resource("server", "ServerController")->except(["index"]);

Route::get("/server/{server}/vote", "ServerController@vote")->name("server.vote");
Route::post("/server/{server}/vote", "ServerController@cast")->name("server.cast");

Auth::routes(["verify"=>true]);
