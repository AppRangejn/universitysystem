<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ScheduleSettingController;
use App\Models\User;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->get('/user', function (\Illuminate\Http\Request $request) {
    return User::with('group')->find($request->user()->id);
});


Route::get('/faculties', [FacultyController::class, 'index']);
Route::get('/faculties/{faculty}', [FacultyController::class, 'show']);

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);

Route::get('/groups', [GroupController::class, 'index']);
Route::get('/groups/{group}', [GroupController::class, 'show']);

Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);


Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::get('/stats', [AdminDashboardController::class, 'stats']);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/groups', GroupController::class);
    Route::apiResource('/courses', CourseController::class);
    Route::apiResource('/faculties', FacultyController::class);
    Route::apiResource('/schedules', ScheduleController::class);

    Route::get('/settings/schedule', [ScheduleSettingController::class, 'index']);
    Route::put('/settings/schedule', [ScheduleSettingController::class, 'update']);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::post('/users/{user}/assign-group', [UserController::class, 'assignGroup']);
});
