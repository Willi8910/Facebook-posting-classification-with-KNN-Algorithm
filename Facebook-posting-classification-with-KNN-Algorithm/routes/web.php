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
    return view('input');
});
Route::get('/admin', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('welcome');
});


Route::get('/a', 'PelanggaranController@cc');
Route::get('/pst', 'PelanggaranController@pst');
Route::get('/cekposting','PelanggaranController@Cek');
Route::get('/valid','PelanggaranController@Valid');
Route::get('/kvalid','PelanggaranController@kValid');
Route::get('/cek',function(){
	return view('input');
});

Route::get('/history','PostingController@listHistory');
Route::get('tag','PelanggaranController@stopControl');
Route::get('savetag','PelanggaranController@saveControl');

Route::get('detailhistory/{id}','PostingController@detailHistory');
Route::get('listPosting','PostingController@listPosting');
Route::get('b','PostingController@hapus');
Route::get('updatePelanggaran','PostingController@updatePosting');
Route::get('test','PostingController@hapushst');
Route::get('deletePelanggaran','PostingController@deletePelanggaran');
Route::get('addPelanggaran','PostingController@addPelanggaran');
Route::get('getPosting','PostingController@getPosting');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
