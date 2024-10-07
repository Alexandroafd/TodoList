<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TacheController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::controller(TacheController::class)->group(function() {
    Route::get('/taches', 'index');
    Route::post('/tache/create', 'store');
    Route::get('/taches/category', 'show');
    Route::put('/tache/{id}', 'update');
    Route::delete('/tache/{id}', 'destroy');
    Route::get('/tache/{id}', 'completed');
    Route::get('/taches/filter', 'filter');
});

Route::get('/notification', [NotificationController::class, 'index']);
Route::post('notification/send', [NotificationController::class, 'sendNotification']);
