<?php

use App\Http\Controllers\Address\AddressController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\Flow\PatientFlowController;
use App\Http\Controllers\History\HistoryController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MKD\MkdController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Report\ReportController;
use App\Services\Api\ApiService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    $logs = app(ApiService::class)->getLogs(env('API_LOG_TOKEN'));
    usort($logs, function($a, $b) {
        // Получаем дату + время как строку
        $datetimeA = $a->log_receipt->date_receipt . ' ' . $a->log_receipt->time_receipt;
        $datetimeB = $b->log_receipt->date_receipt . ' ' . $b->log_receipt->time_receipt;

        // Конвертируем в timestamp для сравнения
        $timestampA = strtotime($datetimeA);
        $timestampB = strtotime($datetimeB);

        // Сравниваем по убыванию — то есть больший timestamp должен идти первым
        return $timestampB <=> $timestampA;
    });
    return view('dashboard', compact('logs'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['permission:access.add'])->group(function () {
        Route::post('/log',[LogController::class, 'store'])->name('log.store');
        Route::get('/log', [LogController::class, 'add'])->name('log.add');
    });
    Route::middleware(['permission:access.delete'])->group(function () {
        Route::delete('/log/{id}',[LogController::class, 'destroy'])->name('log.destroy');
    });
    Route::middleware(['permission:access.edit'])->group(function () {
        Route::get('/log/{id}/update',[LogController::class, 'edit'])->name('log.edit');
        Route::put('/log/{id}',[LogController::class, 'update'])->name('log.update');
    });
    Route::middleware(['permission:report.create'])->group(function () {
        Route::get('/excel', [ExcelController::class, 'getPageStore'])->name('excel.store');
        Route::post('/excel/download', [ExcelController::class, 'downloadExcel'])->name('excel.download');
        Route::post('/excel/download/summary', [ExcelController::class, 'downloadExcelSummary'])->name('excel.download.summary');
    });
    Route::middleware(['permission:report.history'])->group(function () {
        Route::get('/history/report', [ReportController::class, 'index'])->name('history.report');
        Route::get('/reports/download/{id}', [ReportController::class, 'downloadSaved'])
            ->name('reports.download');
    });
    Route::middleware(['permission:statistic'])->group(function () {
        Route::get('/patient/flow', [PatientFlowController::class, 'index'])
            ->name('patient.flow');
    });
    Route::middleware(['permission:history'])->group(function () {
        Route::get('/history', [HistoryController::class, 'index'])->name('history');
    });

    Route::get('/log/{id}', [LogController::class, 'findById'])->name('log.find');
    Route::post('/log/search', [LogController::class, 'getLogByName'])->name('log.search');

    Route::post('/mkd/suggestions/state', [MkdController::class, 'suggestState'])->name('mkd.suggestState');
    Route::post('/mkd/suggestions/wound', [MkdController::class, 'suggestWound'])->name('mkd.suggestWound');

    Route::post('/address/suggest', [AddressController::class, 'suggestAddress'])->name('address.suggest');
    Route::post('/address/suggest/place', [AddressController::class, 'suggestPlace'])->name('address.suggest.place');

});

require __DIR__.'/auth.php';
