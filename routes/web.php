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
Route::get('/', 'NewHomeController@index')->name('home');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', function () {
    return redirect('/');
});

Route::resource('/person', 'PersonController');
Route::post('/search-person', 'PersonController@search');


Route::resource('/student', 'StudentController');
Route::post('/search-student', 'StudentController@search');
Route::post('/search-student2', 'StudentController@search2');
Route::post('/search-student-reception', 'StudentController@searchReception');

Route::resource('/professor', 'ProfessorController');
Route::post('/search-professor', 'ProfessorController@search');
Route::post('/search-professor2', 'ProfessorController@search2');
Route::post('/search-professor-reception', 'ProfessorController@searchReception');

Route::resource('/ticket', 'TicketController');
Route::post('/search-ticket', 'TicketController@search');
Route::post('/search-ticket2', 'TicketController@search2');

Route::resource('/reception', 'ReceptionController');

Route::resource('/menu', 'MenuController');
Route::post('/create-menu-by-week', 'MenuController@createMenuByWeek');
Route::post('/search-menu', 'MenuController@search');
Route::post('/search-all-menu', 'MenuController@searchAll');


Route::resource('/type_menu', 'TypeMenuController');

Route::resource('/report', 'ReportController');
Route::get('/export-excel/{date_start}/{date_end}', 'ReportController@exportExcel');