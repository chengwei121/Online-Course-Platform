<?php

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

// Lesson progress tracking
Route::middleware(['auth', 'web'])->group(function () {
    Route::post('/lessons/{lesson}/progress', [App\Http\Controllers\Client\LessonController::class, 'updateProgress'])->name('api.lessons.progress');
});