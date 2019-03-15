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

Route::group(['prefix' => 'api'], function () {
    Route::post('feedback', 'FeedbackController@store');
});

Route::get('feedback/input', 'FeedbackController@create');

Route::get('feedback/dashboard', 'FeedbackController@index');

Route::get('feedback/data', 'FeedbackController@dashboardData');
