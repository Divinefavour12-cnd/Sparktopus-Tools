<?php

use App\Http\Controllers\SparkAdmin\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => env('APP_ADMIN_PREFIX', 'admin')],
    function () {
        Route::middleware('guest:admin')->group(function () {
            Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
            Route::post('login', [AuthController::class, 'login'])->name('spark-admin.login.post');
        });

        Route::middleware('auth:admin')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('spark-admin.logout');
        });
    }
);

