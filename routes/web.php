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

Auth::routes();

Route::middleware('auth')->get('/', function () {
    return view('home');
})->name('home');

Route::middleware('auth')->get('/transactions/all', 'TransactionController@index');
Route::middleware('auth')->post('/transactions/add', 'TransactionController@add');
Route::middleware('auth')->post('/transactions/truncate', 'TransactionController@deleteAll');

Route::middleware('auth')->get('/courses', 'CurrencyConverterController@getCourses');

// get partners data
Route::middleware('auth')->get('/partner/travelpayouts/get', 'PartnersController@getTravelpayouts');
Route::middleware('auth')->get('/partner/yandex-partner/get', 'PartnersController@getYandexPartner');

