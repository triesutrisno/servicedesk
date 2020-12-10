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

Route::get('/ap1/approve/{kode}', 'HomeController@approve');
Route::get('/ap1/reject/{kode}', 'HomeController@reject');
Route::get('/ap2/approve/{kode}', 'HomeController@approve2');
Route::get('/ap2/reject/{kode}', 'HomeController@reject2');
Route::get('/ap3/approve/{kode}', 'HomeController@approve3');
Route::get('/ap3/reject/{kode}', 'HomeController@reject3');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout','LoginController@logout');

    Route::get('home', 'HomeController@index');
    Route::get('home/detail/{id}', 'HomeController@detail');
    Route::resource('user', 'UserController');
    Route::resource('subservice', 'SubserviceController');
    Route::resource('service', 'ServiceController');
    Route::resource('masterlayanan', 'MasterlayananController');
    Route::resource('aksesservice', 'AksesserviceController');
    Route::resource('mprogress', 'MprogressController');



    Route::get('/tiket', 'TiketController@index');
    Route::get('/tiket/create', 'TiketController@create');
    Route::get('/tiket/create/{id}', 'TiketController@created');
    Route::get('/tiket/create/{id}/{id2}', 'TiketController@add');
    Route::post('/tiket/create/{id}/{id2}', 'TiketController@store');    
    Route::get('/tiket/edit/{id}', 'TiketController@edit');   
    Route::post('/tiket/edit/{id}', 'TiketController@update');
    Route::post('/tiket/delete/{id}', 'TiketController@destroy');
    Route::get('/tiket/detail/{id}', 'TiketController@show');
    Route::post('/tiket/close/{id}', 'TiketController@close');
    
    Route::get('/approvetiket', 'ApprovetiketController@index');
    Route::patch('/approvetiket/approve/{id}', 'ApprovetiketController@approve');
    Route::patch('/approvetiket/reject/{id}', 'ApprovetiketController@reject');
    Route::get('/approvetiket/detail/{id}', 'ApprovetiketController@show');
    
    Route::get('/persetujuantiket', 'PersetujuantiketController@index');
    Route::patch('/persetujuantiket/approve', 'PersetujuantiketController@approve');
    Route::patch('/persetujuantiket/reject/{id}', 'PersetujuantiketController@reject');
    Route::get('/persetujuantiket/detail/{id}', 'PersetujuantiketController@show');
    Route::get('/persetujuantiket/forward/{id}', 'PersetujuantiketController@forward');    
    Route::post('/persetujuantiket/forward/{idTiket}', 'PersetujuantiketController@saveforward');
    
    Route::get('/tugasku', 'TiketdetailController@index');
    Route::post('/tugasku', 'TiketdetailController@index');
    Route::get('/tugasku/solusi/{id}', 'TiketdetailController@create');
    Route::post('/tugasku/solusi/{id}', 'TiketdetailController@store');
    Route::get('/tugasku/forward/{id}', 'TiketdetailController@forward');
    Route::post('/tugasku/forward/{idDetailTiket}/{idTiket}', 'TiketdetailController@saveforward');
    Route::get('/tugasku/detail/{id}', 'TiketdetailController@show');
});

