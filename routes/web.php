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

Route::post('/traktor/store', 'RestApiController@traktorStore');
Route::post('/plot/store', 'RestApiController@plotStore');
Route::post('/work/plot/{plotId}/traktor/{traktoId}/store', 'RestApiController@workPlotStore');
Route::post('/work/plots', 'RestApiController@allWorkPlots');
Route::post('/filter/{filterId}', 'RestApiController@filterTable');
