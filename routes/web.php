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

/*
Route::get('/', function () {
    return view('welcome');
}); 
 */


Route::get('/','LoginController@index')->name('login'); 
Route::post('/login','LoginController@login');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout','LoginController@logout');

    Route::resource('user', 'UserController');
    Route::resource('subservice', 'SubserviceController');
    Route::resource('service', 'ServiceController');

    Route::get('/tiket', 'TiketController@index');
    Route::get('/tiket/create', 'TiketController@create');
    Route::get('/tiket/create/{id}', 'TiketController@created');
    Route::get('/tiket/create/{id}/{id2}', 'TiketController@add');
    Route::post('/tiket/create/{id}/{id2}', 'TiketController@store');    
    Route::get('/tiket/edit/{id}', 'TiketController@edit');   
    Route::post('/tiket/edit/{id}', 'TiketController@update');
    Route::post('/tiket/delete/{id}', 'TiketController@destroy');
});

