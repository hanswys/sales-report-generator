<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Exports\SalesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class SalesReportController extends Controller
{
public function index(Request $request)
{
    $query = Sale::query();

    if ($request->filled('product')) {
        $query->where('product', 'like', '%' . $request->product . '%');
    }
    if ($request->filled('start_date')) {
        $query->where('sale_date', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->where('sale_date', '<=', $request->end_date);
    }
    if ($request->filled('min_quantity')) {
        $query->where('quantity', '>=', $request->min_quantity);
    }
    if ($request->filled('max_quantity')) {
        $query->where('quantity', '<=', $request->max_quantity);
    }
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    $sales = $query->latest()->paginate(10);

    if ($request->ajax()) {
        return view('reports.partials.sales_table', compact('sales'))->render();
    }

    return view('reports.index', compact('sales'));
}

public function exportExcel()
{
    return Excel::download(new \App\Exports\SalesExport, 'sales_report.xlsx');
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
