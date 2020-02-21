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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('harga', 'MasterHargaController');
});
Route::group(['middleware' => 'auth'], function () {
    Route::resource('spb', 'DataSpbController')->except(['create', 'show']);
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('laporan/{subpage}', ['as' => 'laporan.index', 'uses' => 'LaporanController@index']);
    Route::get('laporan/mingguan?{param}', 'LaporanController@index');
});
Route::group(['middleware' => 'auth'], function () {
    Route::resource('petani', 'DataPetaniController')->except(['create', 'show']);
});
Route::group(['middleware' => 'auth'], function () {
    Route::resource('korlap', 'MasterKorlapController')->except(['create', 'show']);
});
Route::group(['middleware' => 'auth'], function () {
    Route::resource('timbangan', 'DataTimbanganController')->except(['create', 'show']);
    Route::post('timbangan/current', ['as' => 'timbangan.currentData', 'uses' => 'DataTimbanganController@currentData']);
});
Route::group(['middleware' => 'auth'], function () {
    Route::resource('kwitansi', 'DataKwitansiController')->except(['show']);
    Route::post('kwitansi/tiket', ['as' => 'kwitansi.tiket', 'uses' => 'DataKwitansiController@tiket']);
    Route::post('kwitansi/detail', ['as' => 'kwitansi.detail', 'uses' => 'DataKwitansiController@detail']);
    Route::post('kwitansi/harga', ['as' => 'kwitansi.harga', 'uses' => 'DataKwitansiController@harga']);
    Route::post('kwitansi/spb', ['as' => 'kwitansi.spb', 'uses' => 'DataKwitansiController@spb']);
    Route::post('kwitansi/timbangan', ['as' => 'kwitansi.timbangan', 'uses' => 'DataKwitansiController@detailTimbangan']);
    Route::post('kwitansi/petani', ['as' => 'kwitansi.petani', 'uses' => 'DataKwitansiController@detailPetani']);
    Route::post('kwitansi/generate', ['as' => 'kwitansi.generate', 'uses' => 'DataKwitansiController@generate']);
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('{page}', ['as' => 'page.index', 'uses' => 'PageController@index']);
});

Route::prefix('/api')->group(function () {
    Route::post('/process', 'FilepondController@upload')->name('filepond.upload');
    Route::delete('/process', 'FilepondController@delete')->name('filepond.delete');
});

