<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Sanctum CSRF-cookie endpoint
Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});

// Ці маршрути працюють через web-middleware (cookie, CSRF тощо)
Route::middleware('web')->group(function () {

});
