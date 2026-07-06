<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeliverableController;
use App\Http\Controllers\Api\V1\MilestoneController;
use App\Http\Controllers\Api\V1\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\RequirementController;
use App\Http\Controllers\Api\V1\TimelineController;
use App\Http\Controllers\Api\V1\ShareController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\FeedbackController;
use App\Http\Controllers\Api\V1\PasswordResetController;

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
        Route::post('forgot-password', [PasswordResetController::class, 'forgetPassword'])->middleware('throttle:5,1');
        Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])->middleware('throttle:5,1');

        Route::middleware('auth:api')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });
    Route::get('share/{token}', [ShareController::class, 'show']);
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('projects', ProjectController::class); // resource will add all routes
        Route::apiResource('projects.milestones', MilestoneController::class)->scoped(); // Ensures milestone belongs to the given project
        Route::patch('projects/{project}/milestones/{milestone}/status', [MilestoneController::class, 'updateStatus']);
        Route::apiResource('projects.requirements', RequirementController::class)->scoped(); // Ensures requirement belongs to the given project
        Route::apiResource('projects.milestones.deliverables', DeliverableController::class)->scoped(); // Ensures deliverable belongs to the given milestone
        Route::apiResource('projects.milestones.payments', PaymentController::class)->scoped(); // Ensures payment belongs to the given milestone
        Route::get('projects/{project}/timeline', [TimelineController::class, 'index']);
        Route::post('projects/{project}/share', [ShareController::class, 'generate']);
        Route::delete('projects/{project}/share', [ShareController::class, 'revoke']);
        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::post('auth/change-password', [AuthController::class, 'changePassword']);
        Route::put('auth/profile', [AuthController::class, 'updateProfile']);
        Route::post('feedback', [FeedbackController::class, 'store']);
    });
});
