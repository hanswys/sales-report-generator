<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\ReportController;

Route::get('/', [SalesReportController::class, 'index'])->name('reports.index');
Route::middleware(['auth'])->group(function () {
Route::get('/excel', [SalesReportController::class, 'exportExcel'])->name('reports.excel');
Route::get('/pdf', [SalesReportController::class, 'exportPDF'])->name('reports.pdf');
Route::post('/reports/upload', [ReportController::class, 'upload'])->name('reports.upload');
Route::get('/reports/{sale}/edit', [ReportController::class, 'edit'])->name('reports.edit');
Route::put('/reports/{sale}', [ReportController::class, 'update'])->name('reports.update');
Route::delete('/reports/{sale}', [ReportController::class, 'destroy'])->name('reports.destroy');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

