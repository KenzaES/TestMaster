<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\TaskController;

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


Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:10,1'); 
Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::post('/logout-all', [AuthenticatedSessionController::class, 'destroyAll'])->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {
   Route::post('/add/tasks', [TaskController::class, 'store']);
   Route::get('/tasks', [TaskController::class, 'index']);
   Route::get('/tasks/{id}', [TaskController::class, 'show']);
   Route::put('/edit/tasks/{id}', [TaskController::class, 'update']);
   Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
});