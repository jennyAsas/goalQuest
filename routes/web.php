<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReflectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubtaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
    Route::post('/goals/{goal}/checkin', [GoalController::class, 'checkin'])->name('goals.checkin');
    Route::post('/goals/{goal}/reschedule', [GoalController::class, 'reschedule'])->name('goals.reschedule');
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');

    Route::post('/goals/{goal}/reflections', [ReflectionController::class, 'store'])->name('reflections.store');
    Route::post('/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
    Route::post('/subtasks/{subtask}/toggle', [SubtaskController::class, 'toggle'])->name('subtasks.toggle');
});

require __DIR__ . '/auth.php';
