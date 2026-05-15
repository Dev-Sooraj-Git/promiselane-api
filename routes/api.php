<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MilestoneController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\RequirementController;

// Dummy login route — prevents Authenticate middleware from crashing
Route::get('login', function () {
    return response()->json([
        'success' => false,
        'message' => 'Unauthenticated.',
    ], 401);
})->name('login');

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:api')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::apiResource('projects', ProjectController::class);// resource will add all routes
        Route::apiResource('projects.milestones', MilestoneController::class);
        Route::patch('projects/{project}/milestones/{milestone}/status', [MilestoneController::class, 'updateStatus']);
        Route::apiResource('projects.requirements', RequirementController::class);
    });
});
