<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AnnouncementController;

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

// Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'announcements'], function () {
        Route::get('/', [AnnouncementController::class, 'index']);
        Route::post('/store', [AnnouncementController::class, 'store']);
        Route::get('/view/{id}', [AnnouncementController::class, 'view']);
        Route::post('/update/{id}', [AnnouncementController::class, 'update']);
        Route::get('/delete/{id}', [AnnouncementController::class, 'delete']);
    });
// });