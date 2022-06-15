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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', function () {
    return redirect('/');
});

Route::resource('/person', 'PersonController');
Route::post('/search-person', 'PersonController@search');


Route::resource('/student', 'StudentController');
Route::post('/search-student', 'StudentController@search');

Route::resource('/professor', 'ProfessorController');
Route::post('/search-professor', 'ProfessorController@search');

Route::resource('/ticket', 'TicketController');

Route::resource('/menu', 'MenuController');
Route::post('/create-menu-by-week', 'MenuController@createMenuByWeek');


Route::resource('/type_menu', 'TypeMenuController');