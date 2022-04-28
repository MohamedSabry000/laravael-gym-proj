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

/* Routes for gyms */
Route::get('/admin/gyms', [App\Http\Controllers\AdminController::class, 'showGyms'])->name('showGyms')->middleware('auth');
Route::get('/admin/addgym', [App\Http\Controllers\GymController::class , 'create'])->name('createGym')->middleware('auth');
Route::post('/admin/storegym', [App\Http\Controllers\GymController::class, 'store'])->name('storeGym')->middleware('auth');
Route::get('/admin/deletegym/{id}', [App\Http\Controllers\GymController::class, 'delete'])->name('deletegym')->middleware('auth');

Route::get('/admin/addCity', [App\Http\Controllers\CityController::class, 'createCity'])->name('createCity')->middleware('auth');
Route::get('/admin/addCity', [App\Http\Controllers\CityController::class, 'create'])->name('create')->middleware('auth');

Route::get('/admin/gymManagers', [App\Http\Controllers\GymManagerController::class, 'showGymManagers'])->name('showGymManagers')->middleware('auth');
Route::get('/admin/gymManager/{id}', [App\Http\Controllers\GymManagerController::class, 'show'])->name('show')->middleware('auth');
Route::get('/admin/gymManagerDel/{id}', [App\Http\Controllers\GymManagerController::class, 'delete'])->name('delete')->middleware('auth');

Route::get('/admin/allusers', [App\Http\Controllers\AdminController::class, 'showUsers'])->name('showUsers')->middleware('auth');
Route::get('/admin/cities', [App\Http\Controllers\CityController::class, 'showCites'])->name('showCites')->middleware('auth');
Route::get('/admin/addCity', [App\Http\Controllers\CityController::class, 'create'])->name('create')->middleware('auth');
Route::put('/admin/storeEditCity/{id}', [App\Http\Controllers\CityController::class, 'storeEdit'])->name('city.storeEdit')->middleware('auth');
Route::get('/admin/addEditCity/{id}', [App\Http\Controllers\CityController::class, 'edit'])->name('city.addEdit')->middleware('auth');
Route::post('/admin/cities', [App\Http\Controllers\CityController::class, 'store'])->name('city.store')->middleware('auth');
Route::get('/admin/cities/{id}', [App\Http\Controllers\CityController::class, 'show'])->name('city.show')->middleware('auth');
Route::get('/admin/delCities/{id}', [App\Http\Controllers\CityController::class, 'delete'])->name('city.delete')->middleware('auth');
// Route::post('/cities', [App\Http\Controllers\CityController::class, 'store'])->name('city.store')->middleware('auth');


Route::get('/admin/allcoaches', [App\Http\Controllers\CoachController::class, 'showCoaches'])->name('showCoaches')->middleware('auth');
Route::get('/admin/addcoach', [App\Http\Controllers\CoachController::class, 'create'])->name('create')->middleware('auth');
Route::post('/allcoaches', [App\Http\Controllers\CoachController::class, 'store'])->name('coach.store')->middleware('auth');
Route::get('/admin/allcoaches/{id}', [App\Http\Controllers\CoachController::class, 'show'])->name('show')->middleware('auth');

// Route::post('/admin/storeCity', [App\Http\Controllers\CityController::class, 'store'])->name('store')->middleware('auth');

// Route::get('admin', function () {
//     return view('admin');
// });
