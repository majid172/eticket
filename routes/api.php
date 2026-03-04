<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Operator\BusController;
use App\Http\Controllers\Api\V1\Operator\CompanyController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Admin\BusController as AdminBusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Role Separation:
|   user     → Can only register, login, search schedules, and book tickets.
|   operator → Can manage their own buses, routes, schedules, and bookings.
|   admin    → Full platform control (users, companies, all bookings, settings).
|
| Passengers (user role) have NO access to operator or admin panels.
*/

// ─── Public Routes (No Auth Required) ────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// ─── Authenticated Routes ─────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Shared auth endpoints (all roles)
    Route::prefix('auth')->group(function () {
        Route::get('/me',      [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // ─── Passenger (User) Routes ──────────────────────────────────────────────
    // Passengers can ONLY search schedules and manage their own bookings.
    // They have NO access to operator or admin routes.
    Route::middleware('role:passenger')->prefix('passenger')->group(function () {
        // Search available schedules/buses
        Route::get('/schedules',              [\App\Http\Controllers\Api\V1\Passenger\ScheduleController::class, 'index']);
        Route::get('/schedules/{id}/seats',   [\App\Http\Controllers\Api\V1\Passenger\ScheduleController::class, 'seats']);

        // Booking management (own bookings only)
        Route::get('/bookings',               [\App\Http\Controllers\Api\V1\Passenger\BookingController::class, 'index']);
        Route::post('/bookings',              [\App\Http\Controllers\Api\V1\Passenger\BookingController::class, 'store']);
        Route::get('/bookings/{id}',          [\App\Http\Controllers\Api\V1\Passenger\BookingController::class, 'show']);
        Route::delete('/bookings/{id}',       [\App\Http\Controllers\Api\V1\Passenger\BookingController::class, 'destroy']);
    });

    // ─── Operator Routes ──────────────────────────────────────────────────────
    // Operators manage their own company resources. Users cannot access these.
    Route::middleware('role:operator')->prefix('operator')->group(function () {
        // Company profile
        Route::get('/company',  [CompanyController::class, 'show']);
        Route::post('/company', [CompanyController::class, 'store']);
        Route::put('/company',  [CompanyController::class, 'update']);
       

        // Bus management (operator's own fleet only)
        Route::get('/buses',           [BusController::class, 'index']);
        Route::post('/buses',          [BusController::class, 'store']);
        Route::get('/buses/{id}',      [BusController::class, 'show']);
        Route::put('/buses/{id}',      [BusController::class, 'update']);
        Route::delete('/buses/{id}',   [BusController::class, 'destroy']);

        // Route management
        Route::get('/routes',                 [\App\Http\Controllers\Api\V1\Operator\RouteController::class, 'index']);
        Route::post('/routes',                [\App\Http\Controllers\Api\V1\Operator\RouteController::class, 'store']);

        // Schedule management
        Route::get('/schedules',              [\App\Http\Controllers\Api\V1\Operator\ScheduleController::class, 'index']);
        Route::post('/schedules',             [\App\Http\Controllers\Api\V1\Operator\ScheduleController::class, 'store']);
        Route::put('/schedules/{id}',         [\App\Http\Controllers\Api\V1\Operator\ScheduleController::class, 'update']);
        Route::delete('/schedules/{id}',      [\App\Http\Controllers\Api\V1\Operator\ScheduleController::class, 'destroy']);

        // Booking management (view passengers on their buses)
        Route::get('/bookings',               [\App\Http\Controllers\Api\V1\Operator\BookingController::class, 'index']);
        Route::put('/bookings/{id}/status',   [\App\Http\Controllers\Api\V1\Operator\BookingController::class, 'updateStatus']);
    });

    // ─── Admin Routes ─────────────────────────────────────────────────────────
    // Full platform control. Only admin role can access.
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // User management
        Route::get('/users',             [UserController::class, 'index']);
        Route::get('/users/{id}',        [UserController::class, 'show']);
        Route::put('/users/{id}',        [UserController::class, 'update']);
        Route::put('/users/{id}/status', [UserController::class, 'toggleStatus']);
        Route::delete('/users/{id}',     [UserController::class, 'destroy']);

        // Company/Operator approval
        Route::get('/companies',              [\App\Http\Controllers\Api\V1\Admin\CompanyController::class, 'index']);
        Route::put('/companies/{id}/status',  [\App\Http\Controllers\Api\V1\Admin\CompanyController::class, 'updateStatus']);

        // Platform-wide Buses
        Route::get('/buses',                  [AdminBusController::class, 'index']);

        // Platform-wide bookings
        Route::get('/bookings',               [\App\Http\Controllers\Api\V1\Admin\BookingController::class, 'index']);

        // Routes & Schedules oversight
        Route::get('/routes',                 [\App\Http\Controllers\Api\V1\Admin\RouteController::class, 'index']);
        Route::get('/schedules',              [\App\Http\Controllers\Api\V1\Admin\ScheduleController::class, 'index']);
    });
});

