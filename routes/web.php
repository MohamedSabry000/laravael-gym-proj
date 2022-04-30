<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GymController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\CityManagerController;


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
Route::get('/admin/cities', [App\Http\Controllers\CityController::class, 'showCites'])->name('showCites')->middleware('auth');
Route::get('/admin/allusers', [App\Http\Controllers\AdminController::class, 'showUsers'])->name('showUsers')->middleware('auth');


/* Routes for gyms */
Route::get('/admin/gyms', [GymController::class, 'showGyms'])->name('showGyms')->middleware('auth');
Route::get('/admin/addgym', [GymController::class , 'create'])->name('createGym')->middleware('auth');
Route::post('/admin/storegym', [GymController::class, 'store'])->name('storeGym')->middleware('auth');
Route::get('/admin/deletegym/{id}', [GymController::class, 'delete'])->name('deleteGym')->middleware('auth');
Route::get('/admin/gym/{id}', [GymController::class, 'show'])->name('showGym')->middleware('auth');
Route::get('/admin/addEditGym/{id}', [GymController::class, 'edit'])->name('gym.addEdit')->middleware('auth');
Route::put('/admin/storeEditGym/{id}', [GymController::class, 'editGym'])->name('gym.storeEditGym')->middleware('auth');

/**         Gym Managers         */
Route::get('/admin/gymManagers', [App\Http\Controllers\GymManagerController::class, 'showGymManagers'])->name('showGymManagers')->middleware('auth');

Route::get('/admin/addGymManager', [App\Http\Controllers\GymManagerController::class, 'create'])->name('gymManager.create')->middleware('auth');
Route::post('/admin/gymManagers', [App\Http\Controllers\GymManagerController::class, 'store'])->name('gymManager.store')->middleware('auth');

Route::get('/admin/gymManager/{id}', [App\Http\Controllers\GymManagerController::class, 'show'])->name('show')->middleware('auth');
Route::get('/admin/gymManagerDel/{id}', [App\Http\Controllers\GymManagerController::class, 'delete'])->name('delete')->middleware('auth');
Route::get('/admin/addEditManager/{id}', [App\Http\Controllers\GymManagerController::class, 'edit'])->name('manager.addEdit')->middleware('auth');
Route::put('/admin/storeEditManager/{id}', [App\Http\Controllers\GymManagerController::class, 'editManager'])->name('manager.storeEdit')->middleware('auth');
/************************ */
Route::get('/admin/allusers', [App\Http\Controllers\AdminController::class, 'showUsers'])->name('showUsers')->middleware('auth');

/**         Cities         */
Route::get('/admin/cities', [App\Http\Controllers\CityController::class, 'showCites'])->name('showCites')->middleware('auth');

Route::get('/admin/addCity', [App\Http\Controllers\CityController::class, 'create'])->name('create')->middleware('auth');
Route::post('/admin/cities', [App\Http\Controllers\CityController::class, 'store'])->name('city.store')->middleware('auth');

Route::get('/admin/addEditCity/{id}', [App\Http\Controllers\CityController::class, 'edit'])->name('city.addEdit')->middleware('auth');
Route::put('/admin/storeEditCity/{id}', [App\Http\Controllers\CityController::class, 'editCity'])->name('city.storeEdit')->middleware('auth');

Route::get('/admin/cities/{id}', [App\Http\Controllers\CityController::class, 'show'])->name('city.show')->middleware('auth');
Route::get('/admin/delCities/{id}', [App\Http\Controllers\CityController::class, 'delete'])->name('city.delete')->middleware('auth');


/**         City Managers         */
Route::get('/admin/allCityManagers', [App\Http\Controllers\CityManagerController::class, 'showCityManager'])->name('showCityManager')->middleware('auth');

Route::get('/admin/addCityManager', [App\Http\Controllers\CityManagerController::class, 'create'])->name('create')->middleware('auth');
Route::post('/allCityManagers', [App\Http\Controllers\CityManagerController::class, 'store'])->name('cityManager.store')->middleware('auth');

Route::get('/admin/addEditCityManager/{id}', [App\Http\Controllers\CityManagerController::class, 'edit'])->name('citymanager.addEdit')->middleware('auth');
Route::put('/admin/storeEditCityManager/{id}', [App\Http\Controllers\CityManagerController::class, 'editCityManager'])->name('citymanager.storeEdit')->middleware('auth');

Route::get('/admin/allCityManagers/{id}', [App\Http\Controllers\CityManagerController::class, 'show'])->name('citymanager.show')->middleware('auth');
Route::get('/admin/delCityManagers/{id}', [App\Http\Controllers\CityManagerController::class, 'deleteCityManager'])->name('citymanager.delete')->middleware('auth');

/************************* */
/**         Coches         */
Route::get('/admin/allcoaches', [App\Http\Controllers\CoachController::class, 'showCoaches'])->name('showCoaches')->middleware('auth');
Route::get('/admin/addcoach', [App\Http\Controllers\CoachController::class, 'create'])->name('create')->middleware('auth');
Route::post('/allcoaches', [App\Http\Controllers\CoachController::class, 'store'])->name('coach.store')->middleware('auth');

Route::get('/admin/addEditCoach/{id}', [App\Http\Controllers\CoachController::class, 'edit'])->name('coach.addEdit')->middleware('auth');
Route::put('/admin/storeEditCoach/{id}', [App\Http\Controllers\CoachController::class, 'editCoach'])->name('coach.storeEdit')->middleware('auth');

Route::get('/admin/allcoaches/{id}', [App\Http\Controllers\CoachController::class, 'show'])->name('coach.show')->middleware('auth');
Route::get('/admin/delCoaches/{id}', [App\Http\Controllers\CoachController::class, 'delete'])->name('coach.delete')->middleware('auth');
/************************* */


////***************************traning packages */

Route::get('/admin/tarning-packages', [App\Http\Controllers\TrainingPackagesController::class, 'showPackages'])->name('showPackages')->middleware('auth');
Route::get('/admin/addTraningPackage', [App\Http\Controllers\TrainingPackagesController::class, 'create'])->name('traningPackage.create')->middleware('auth');
Route::post('/tarning-packages', [App\Http\Controllers\TrainingPackagesController::class, 'store'])->name('traningPackage.store')->middleware('auth');
Route::get('/admin/tarning-packages/{id}', [App\Http\Controllers\TrainingPackagesController::class, 'show'])->name('trainingPackeges.show')->middleware('auth');
Route::get('/admin/delTaraningPackage/{id}', [App\Http\Controllers\TrainingPackagesController::class, 'deletePackage'])->name('trainingPackage.delete')->middleware('auth');
// Route::post('/admin/storeCity', [App\Http\Controllers\CityController::class, 'store'])->name('store')->middleware('auth');

Route::get('/admin/addEditPackage/{id}', [App\Http\Controllers\TrainingPackagesController::class, 'edit'])->name('traningPackage.edit')->middleware('auth');
Route::put('/admin/storeEditPackage/{id}', [App\Http\Controllers\TrainingPackagesController::class, 'editPackage'])->name('traningPackage.storeEdit')->middleware('auth');

////***************************traning sessions */

Route::get('/admin/tarning-sessions', [App\Http\Controllers\TrainingController::class, 'showSessions'])->name('showSessions')->middleware('auth');
Route::get('/admin/addTraningSession', [App\Http\Controllers\TrainingController::class, 'create'])->name('traningSession.create')->middleware('auth');
Route::post('/tarning-sessions', [App\Http\Controllers\TrainingController::class, 'store'])->name('traningSession.store')->middleware('auth');
Route::get('/admin/tarning-sessions/{id}', [App\Http\Controllers\TrainingController::class, 'show'])->name('trainingSession.show')->middleware('auth');
Route::get('/admin/delTaraningSession/{id}', [App\Http\Controllers\TrainingController::class, 'deleteSession'])->name('trainingSession.delete')->middleware('auth');

Route::get('/admin/addEditSession/{id}', [App\Http\Controllers\TrainingController::class, 'edit'])->name('traningSession.edit')->middleware('auth');
Route::put('/admin/storeEditSession/{id}', [App\Http\Controllers\TrainingController::class, 'editSession'])->name('traningSession.storeEdit')->middleware('auth');




//****************************Attendance Table */
Route::get('/admin/attendance', [App\Http\Controllers\AttendanceController::class, 'showAttendance'])->name('showAttendance')->middleware('auth');


/**************************** Stripe ********* */
Route::get('stripe/buyPackage', [StripePaymentController::class, 'stripe']);
Route::post('stripe/buyPackage', [StripePaymentController::class, 'stripePost'])->name('stripe.post');
// Route::get('admin', function () {
//     return view('admin');
// });