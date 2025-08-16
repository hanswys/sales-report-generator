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

    $totalRevenue = $sales->sum('price');
    $totalItems = $sales->sum('quantity');

    // Prepare chart data
    $revenueByProduct = $sales->groupBy('product')->map(function ($group) {
        return $group->sum('price');
    });

    $monthlySales = $sales->groupBy(function($item) {
        return \Carbon\Carbon::parse($item->sale_date)->format('Y-m');
    })->map(function ($group) {
        return $group->sum('price');
    });

    // Generate chart images (using QuickChart API for example)
    $chartImages = [
        'revenueByProduct' => "https://quickchart.io/chart?c=" . urlencode(json_encode([
            'type' => 'bar',
            'data' => [
                'labels' => array_keys($revenueByProduct->toArray()),
                'datasets' => [[
                    'label' => 'Revenue',
                    'data' => array_values($revenueByProduct->toArray())
                ]]
            ]
        ])),
        'monthlySales' => "https://quickchart.io/chart?c=" . urlencode(json_encode([
            'type' => 'line',
            'data' => [
                'labels' => array_keys($monthlySales->toArray()),
                'datasets' => [[
                    'label' => 'Monthly Sales',
                    'data' => array_values($monthlySales->toArray())
                ]]
            ]
        ])),
    ];

    $pdf = Pdf::loadView('reports.pdf', compact('sales', 'totalRevenue', 'totalItems', 'chartImages'));
    return $pdf->download('sales_report.pdf');
}
}
