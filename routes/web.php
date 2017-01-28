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

Route::get('/','HALAteeqControllers\FreelancerController@main');
Route::get('/sbadmin','HALAteeqControllers\SBAdminController@main');
Route::get('/profile','HALAteeqControllers\ProfileController@main');
Route::post('/updateprofile','HALAteeqControllers\ProfileController@updateProfile');

Auth::routes();
