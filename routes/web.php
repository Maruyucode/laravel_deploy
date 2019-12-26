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

Route::resource('tasks', 'TasksController')->only([
    //onlyで使用する関数を限定できる
    'index', 'store', 'edit', 'update', 'destroy'
]);

Auth::routes();

// homeというURLにきたらHomeControllerのindexという関数を実行しろ
Route::get('/home', 'TasksController@index')->name('home');
