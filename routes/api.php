<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TasksController;

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


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::any('/tasks', [TasksController::class, 'list']);
    Route::post('/tasks/create', [TasksController::class, 'create']);
    Route::post('/tasks/update', [TasksController::class, 'update']);
    Route::post('/tasks/delete', [TasksController::class, 'delete']);
    Route::get('/logout', [UserController::class, 'logout']);
});