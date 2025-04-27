<?php

use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Services\Api\ApiService;
use App\Services\LogService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $logs = json_decode(app(ApiService::class)->getLogs(env('API_LOG_TOKEN'))->body());
    return view('dashboard', compact('logs'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/log',[LogController::class, 'store'])->name('log.store');
    Route::delete('/log/{id}',[LogController::class, 'destroy'])->name('log.destroy');
    Route::get('/log/{id}/update',[LogController::class, 'edit'])->name('log.edit');
    Route::put('/log/{id}',[LogController::class, 'update'])->name('log.update');
    Route::get('/logs', [LogController::class, 'findAll'])->name('logs');
    Route::get('/log/{id}', [LogController::class, 'findById'])->name('log.find');
    Route::get('/log', [LogController::class, 'add'])->name('log.add');

});

require __DIR__.'/auth.php';
