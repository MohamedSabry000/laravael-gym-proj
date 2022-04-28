<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('index')->middleware('auth');
Route::get('/admin/allusers', [App\Http\Controllers\AdminController::class, 'showUsers'])->name('showUsers')->middleware('auth');
Route::get('/admin/cities', [App\Http\Controllers\CityController::class, 'showCites'])->name('showCites')->middleware('auth');
Route::get('/admin/allgyms', [App\Http\Controllers\AdminController::class, 'showGyms'])->name('showGyms')->middleware('auth');
Route::get('/admin/addCity', [App\Http\Controllers\CityController::class, 'createCity'])->name('createCity')->middleware('auth');
Route::get('/admin/gymManager', [App\Http\Controllers\GymManagerController::class, 'showGymManager'])->name('showGymManager')->middleware('auth');

// Route::get('admin', function () {
//     return view('admin');
// });
