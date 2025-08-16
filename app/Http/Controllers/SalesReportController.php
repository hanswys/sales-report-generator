<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Exports\SalesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
public function index()
{
$sales = Sale::latest()->paginate(10);
return view('reports.index', compact('sales'));
}

public function exportExcel()
{
return Excel::download(new SalesExport, 'sales_report.xlsx');
}

public function exportPDF()
{
$sales = Sale::all();
$pdf = Pdf::loadView('reports.pdf', compact('sales'));
return $pdf->download('sales_report.pdf');
}
}
