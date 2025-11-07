<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});

