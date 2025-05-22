<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/tasks/report', [TaskController::class, 'report'])->name('tasks.report');
    Route::resource('tasks', TaskController::class);
    Route::post('/tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('tasks.toggle-complete');
    Route::post('/tasks/update-order', [TaskController::class, 'updateOrder'])->name('tasks.update-order');
    Route::get('/tasks/create-test', [TaskController::class, 'createTestTask'])->name('tasks.create-test');
    
    Route::resource('categories', CategoryController::class);

    // Temporary debug route to display all tasks and their due dates
    Route::get('/debug/tasks', function () {
        $tasks = App\Models\Task::all(['id', 'title', 'due_date']);
        return response()->json($tasks);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Theme toggle route
    Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');
});

require __DIR__.'/auth.php'; 