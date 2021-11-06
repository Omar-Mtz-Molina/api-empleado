<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\SignOutController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
return $request->user();
}); */

Route::group(['middleware' => 'api'],function($router) {
    Route::post('/signin', [LoginController::class, '__invoke']);
    Route::get('/info', [InfoController::class, '__invoke']);
    Route::post('/signout', [SignOutController::class, '__invoke']);
    Route::get('/getProfile', [ProfileController::class, 'getProfile']);
});