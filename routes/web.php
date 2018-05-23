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

Route::get('/', 'JobController@index');
Route::get('/job/{id}', 'JobController@show');
Route::get('/featured-job/create', 'FeaturedJobController@create');
Route::post('/featured-job/store', 'FeaturedJobController@store');

Route::post('/newsletter', 'NewsletterController@store');

Route::post('/images/upload', 'UploadImagesController@store');

Route::group([ 'middleware' => 'admin' ], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/unpublished/{id}', 'DashboardController@show');
});

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
