<?php

use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Task;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

Route::get('/developers', [DeveloperController::class, 'index'])->name('developers.index');