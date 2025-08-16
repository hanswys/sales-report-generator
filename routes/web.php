<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesReportController;

Route::get('/', [SalesReportController::class, 'index'])->name('reports.index');
Route::get('/excel', [SalesReportController::class, 'exportExcel'])->name('reports.excel');
Route::get('/pdf', [SalesReportController::class, 'exportPDF'])->name('reports.pdf');
