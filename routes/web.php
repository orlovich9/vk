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

//Route::get('/', 'VkController@show')->name('main');
Route::get('/', 'ExtController@show')->name('main');
Route::get('test', 'ExtController@getTest');
Route::post('test', 'ExtController@getTest');
Route::post('create', 'ExtController@create');
Route::post('update', 'ExtController@update');
//Route::get('api', 'VkController@showVkGroupData')->name('api');
