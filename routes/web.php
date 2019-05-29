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
//    return view('welcome');
    return redirect('/app');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Route::resource('topics', "TopicsController")->middleware('can:view,topic');  //Authorization with combining middleware and Policy
Route::resource('topics', "TopicsController");

Route::prefix('{topic}')->group(function () {
    Route::resource('pages', 'PagesController');
    Route::post('pages/{page}/upload', 'PagesController@upload');
    Route::delete('pages/{page}/{pieceLocationOnPage}', 'PagesController@deletePieceImage');
});

Route::get('/app/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*')->middleware('auth');