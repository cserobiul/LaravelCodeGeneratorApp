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

//Route::get('/', function () {
//    return view('welcome');
//});


Route::get('/',\App\Http\Controllers\HomeController::class,'codeCreate')->name('code.create');
Route::post('/code', [App\Http\Controllers\HomeController::class, 'codeStore'])->name('code.store');
Route::get('/codes', [App\Http\Controllers\HomeController::class, 'codeIndex'])->name('code.index');
