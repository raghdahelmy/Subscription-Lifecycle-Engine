<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PlanController;
use App\Http\Middleware\CheckSubscriptionAccess;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('plans', PlanController::class)->only(['index', 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::apiResource('plans', PlanController::class)->only(['store', 'update', 'destroy']);
    Route::post('/webhook/payment-failed', [SubscriptionController::class, 'paymentFailed']);
    Route::middleware(CheckSubscriptionAccess::class)->group(function () {
        //any routes here need subscription access
    });
});

