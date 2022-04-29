<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\SessionsController;
use App\Http\Controllers\Api\PackagesController;
use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use App\Models\TrainingSession;
use App\Models\TrainingPackage;


use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Auth;
/*

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->group(function () {
    Route::post('signin', 'signin');
    Route::post('signup', 'signup');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
});








