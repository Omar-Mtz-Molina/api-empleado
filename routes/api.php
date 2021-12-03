<?php

use App\Http\Controllers\HomeOfficePersonalController;
use App\Http\Controllers\HomeOfficeStatusController;
use App\Http\Controllers\HomeOfficeWeekController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignOutController;
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

// Acceso al Portal Empleados IUSA
Route::post('/signin', [LoginController::class, '__invoke']);

Route::group(['middleware' => 'auth:api'], function ($router) {
    // Acceso al Portal Empleados IUSA
    Route::get('/info', [InfoController::class, '__invoke']);
    Route::get('/hoStatus', [HomeOfficeStatusController::class, 'hoStatus']);
    Route::post('/signout', [SignOutController::class, '__invoke']);
    //Perfil de Usuarios
    Route::get('/getProfile', [ProfileController::class, 'getProfile']);
    //Recibos de Nómina
    Route::get('/getMonths', [PayrollController::class, 'getMonths']);
    Route::post('/getPayroll', [PayrollController::class, 'getPayroll']);
    //Evaluaciones Semanales
    Route::post('/hoWeek', [HomeOfficeWeekController::class, 'hoWeek']);
    Route::get('/hoWeekReviews', [HomeOfficeWeekController::class, 'hoWeekReviews']);
    Route::post('/hoWeekDirector', [HomeOfficeWeekController::class, 'hoWeekDirector']);
    Route::get('/hoAreaReview', [HomeOfficeWeekController::class, 'hoAreaReview']);
    Route::post('/hoAreas', [HomeOfficeWeekController::class, 'hoAreas']);
    Route::post('/hoWeekDirectorTeam', [HomeOfficeWeekController::class, 'hoWeekDirectorTeam']);
    //Evaluaciones Personales
    Route::get('/getHOPersonalPoll', [HomeOfficePersonalController::class, 'getHOPersonalPoll']);
    Route::get('/getSections', [HomeOfficePersonalController::class, 'getSections']);
    Route::post('/HOPersonalQuestions', [HomeOfficePersonalController::class, 'HOPersonalQuestions']);
    Route::post('/HOPersonalConclusion', [HomeOfficePersonalController::class, 'HOPersonalConclusion']);
    Route::post('/HOPersonalAnswareFather', [HomeOfficePersonalController::class, 'HOPersonalAnswareFather']);
    Route::post('/HOPersonalAnswareChild', [HomeOfficePersonalController::class, 'HOPersonalAnswareChild']);
});
