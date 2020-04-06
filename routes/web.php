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

Route::get("/server/{server}/vote", "VoteController@vote")->name("server.vote");
Route::post("/server/{server}/vote", "VoteController@cast")->name("server.cast");

Route::get("/analytics/{server}", "AnalyticsController@basic")->name("analytics.basic");

Route::name("account.")->prefix("account")->group(function(){
    Route::get("servers", "AccountController@servers")->name("servers");
    Route::get("settings", "AccountController@settings")->name("settings");
    Route::put("settings/details", "AccountController@details")->name("settings.details");
    Route::put("settings/password", "AccountController@password")->name("settings.password");
    Route::delete("/", "AccountController@destory")->name("destroy");
});

Route::name("auction.")->prefix("sponsored")->group(function(){
    Route::get("/", ["uses"=>"BiddingController@auction"])->name("current");
    Route::post("/", ["uses"=>"BiddingController@bid"])->name("bid");
});


Route::get("/legal/terms", function(){
    return view("legal.terms");
})->name("legal.terms");

Route::get("/legal/privacy", function(){
    return view("legal.privacy");
})->name("legal.privacy");

Auth::routes(["verify"=>true]);
