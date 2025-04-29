<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function () {
        Route::post('/tasks', [TaskController::class, 'create']);
        Route::get('/tasks', [TaskController::class, 'list']);
        Route::patch('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
        Route::patch('/tasks/{id}/assign', [TaskController::class, 'assign']);
    });
