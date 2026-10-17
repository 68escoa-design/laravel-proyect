<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;

// Auth
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // === ADMIN ROUTES ===
    Route::middleware('role:admin')->group(function () {
        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Clients
        Route::get('/clients', [ClientController::class, 'index']);
        Route::get('/clients/{id}', [ClientController::class, 'show']);
        Route::post('/clients', [ClientController::class, 'store']);
        Route::put('/clients/{id}', [ClientController::class, 'update']);
        Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

        // Plans
        Route::get('/plans', [PlanController::class, 'index']);
        Route::get('/plans/{id}', [PlanController::class, 'show']);
        Route::post('/plans', [PlanController::class, 'store']);
        Route::put('/plans/{id}', [PlanController::class, 'update']);
        Route::delete('/plans/{id}', [PlanController::class, 'destroy']);

        // Payments
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::get('/payments/{id}', [PaymentController::class, 'show']);
        Route::post('/payments', [PaymentController::class, 'store']);
        Route::put('/payments/{id}', [PaymentController::class, 'update']);
        Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);
        Route::get('/payments/client/{id}', [PaymentController::class, 'clientPayments']);
        Route::get('/payments/client/{id}/status', [PaymentController::class, 'clientAccountStatus']);

        // Exercises
        Route::get('/exercises', [ExerciseController::class, 'index']);
        Route::get('/exercises/{id}', [ExerciseController::class, 'show']);
        Route::post('/exercises', [ExerciseController::class, 'store']);
        Route::put('/exercises/{id}', [ExerciseController::class, 'update']);
        Route::delete('/exercises/{id}', [ExerciseController::class, 'destroy']);

        // Schedules
        Route::get('/schedules', [ScheduleController::class, 'index']);
        Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::put('/schedules/{id}', [ScheduleController::class, 'update']);
        Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);

        // Attendance
        Route::get('/attendance', [AttendanceController::class, 'index']);
        Route::get('/attendance/{id}', [AttendanceController::class, 'show']);
        Route::post('/attendance', [AttendanceController::class, 'store']);
        Route::put('/attendance/{id}', [AttendanceController::class, 'update']);
        Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy']);
    });

    // === TRAINER ROUTES ===
    Route::middleware('role:trainer')->group(function () {
        // View clients
        Route::get('/trainer/clients', [ClientController::class, 'index']);

        // Exercises (read only)
        Route::get('/exercises', [ExerciseController::class, 'index']);
        Route::get('/exercises/{id}', [ExerciseController::class, 'show']);

        // Routines
        Route::get('/routines', [RoutineController::class, 'index']);
        Route::get('/routines/{id}', [RoutineController::class, 'show']);
        Route::post('/routines', [RoutineController::class, 'store']);
        Route::put('/routines/{id}', [RoutineController::class, 'update']);
        Route::delete('/routines/{id}', [RoutineController::class, 'destroy']);
        Route::get('/routines/client/{id}', [RoutineController::class, 'clientRoutines']);
    });

    // === CLIENT ROUTES ===
    Route::middleware('role:client')->group(function () {
        // My profile
        Route::get('/my-profile', [UserController::class, 'myProfile']);

        // My account status
        Route::get('/my-account-status', [PaymentController::class, 'clientAccountStatus']);

        // My routines
        Route::get('/my-routines', [RoutineController::class, 'myRoutines']);

        // My attendance
        Route::get('/my-attendance', [AttendanceController::class, 'myAttendance']);
    });
});
