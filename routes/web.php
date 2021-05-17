<?php

use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', 'Controller@index')->name('home');
Route::get('/import', 'Controller@import')->name('import');
Route::post('/import','Controller@getAst')->name('getAST');
Route::get('/api', 'Controller@importApi')->name('importApi');
Route::post('/api','ApiController@detailsCodeAPI')->name('getDetailsCode');
