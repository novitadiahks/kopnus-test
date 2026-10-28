
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ActivityLogController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProjectController::class, 'index'])
        ->name('dashboard');

    Route::post('/projects', [ProjectController::class, 'store'])
        ->name('projects.store');

    Route::get('/projects/{project}', [ProjectController::class, 'show'])
        ->name('projects.show');

    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])
        ->name('tasks.store');

    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('tasks.updateStatus');

    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy');

    Route::patch('/api/tasks/{task}/status', [
        TaskController::class,
        'updateStatusJson',
    ])
        ->middleware('throttle:10,1')
        ->name('api.tasks.updateStatus');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index');

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');
});
