<?php

use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/log',[LogController::class, 'store'])->name('log.store');
    Route::delete('/log/{id}',[LogController::class, 'destroy'])->name('log.destroy');
    Route::put('/log/{id}',[LogController::class, 'update'])->name('log.update');
    Route::get('/logs', [LogController::class, 'findAll'])->name('logs');

});

require __DIR__.'/auth.php';
