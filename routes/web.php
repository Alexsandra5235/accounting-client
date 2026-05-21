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
    $apiService = app(ApiService::class);
    $logs = $apiService->getLogs(config('api.log_token'));

    // Сортируем все записи по дате/времени (новые сверху)
    usort($logs, function($a, $b) {
        $datetimeA = $a->log_receipt->date_receipt . ' ' . $a->log_receipt->time_receipt;
        $datetimeB = $b->log_receipt->date_receipt . ' ' . $b->log_receipt->time_receipt;

        $timestampA = strtotime($datetimeA);
        $timestampB = strtotime($datetimeB);

        return $timestampB <=> $timestampA;
    });

    // Разделяем на текущих и выписанных
    $currentPatients = [];
    $dischargedPatients = [];

    foreach ($logs as $log) {
        if (!empty($log->log_discharge->datetime_discharge)) {
            $dischargedPatients[] = $log;
        } else {
            $currentPatients[] = $log;
        }
    }

    // Получаем текущую страницу для каждого таба из запроса
    $currentPage = request()->get('current_page', 1);
    $dischargedPage = request()->get('discharged_page', 1);
    $perPage = 10; // Количество записей на странице

    // Создаем пагинацию для текущих пациентов
    $currentPatientsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
        array_slice($currentPatients, ($currentPage - 1) * $perPage, $perPage),
        count($currentPatients),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'pageName' => 'current_page']
    );

    // Создаем пагинацию для выписанных пациентов
    $dischargedPatientsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
        array_slice($dischargedPatients, ($dischargedPage - 1) * $perPage, $perPage),
        count($dischargedPatients),
        $perPage,
        $dischargedPage,
        ['path' => request()->url(), 'pageName' => 'discharged_page']
    );

    return view('dashboard', compact('currentPatientsPaginated', 'dischargedPatientsPaginated'));
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
