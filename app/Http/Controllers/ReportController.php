<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        $required = ['product', 'quantity', 'price', 'date'];
        if (array_diff($required, $header)) {
            return back()->withErrors(['CSV missing required columns.']);
        }

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $row = array_combine($header, $data);
            if (!is_numeric($row['quantity']) || !is_numeric($row['price']) || empty($row['product']) || empty($row['date'])) {
                continue;
            }
            $rows[] = [
                'product' => $row['product'],
                'quantity' => (int)$row['quantity'],
                'price' => (float)$row['price'],
                'sale_date' => $row['date'],
            ];
        }
        fclose($handle);

        DB::table('sales')->insert($rows);

        return back()->with('success', 'CSV imported successfully!');
    }

public function edit($id)
{
    $sale = DB::table('sales')->where('id', $id)->first();
    return view('reports.edit', compact('sale'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'product' => 'required',
        'quantity' => 'required|numeric',
        'price' => 'required|numeric',
        'sale_date' => 'required|date',
    ]);
    DB::table('sales')->where('id', $id)->update($request->only('product', 'quantity', 'price', 'sale_date'));
    return redirect()->route('reports.index')->with('success', 'Sale updated!');
}

public function destroy($id)
{
    DB::table('sales')->where('id', $id)->delete();
    return back()->with('success', 'Sale deleted!');
}

public function pdf()
{
    $sales = DB::table('sales')->get();

    // Sales summary
    $totalRevenue = $sales->sum('price');
    $totalItems = $sales->sum('quantity');

    // Prepare data for charts
    $revenueByProduct = $sales->groupBy('product')->map(function ($group) {
        return $group->sum('price');
    });

    $monthlySales = $sales->groupBy(function($item) {
        return \Carbon\Carbon::parse($item->sale_date)->format('Y-m');
    })->map(function ($group) {
        return $group->sum('price');
    });

    // Render Chart.js charts to images (see step 2)
    $chartImages = $this->generateChartImages($revenueByProduct, $monthlySales);

    $pdf = Pdf::loadView('reports.pdf', compact(
        'sales', 'totalRevenue', 'totalItems', 'chartImages'
    ));

    return $pdf->download('sales_report.pdf');
}

// Helper method to generate chart images
// (removed duplicate demo implementation)

protected function generateChartImages($revenueByProduct, $monthlySales)
{
    $productLabels = json_encode(array_keys($revenueByProduct->toArray()));
    $productData = json_encode(array_values($revenueByProduct->toArray()));

    $monthlyLabels = json_encode(array_keys($monthlySales->toArray()));
    $monthlyData = json_encode(array_values($monthlySales->toArray()));

    $revenueChartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode([
        'type' => 'bar',
        'data' => [
            'labels' => array_keys($revenueByProduct->toArray()),
            'datasets' => [[
                'label' => 'Revenue',
                'data' => array_values($revenueByProduct->toArray())
            ]]
        ]
    ]));

    $monthlyChartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode([
        'type' => 'line',
        'data' => [
            'labels' => array_keys($monthlySales->toArray()),
            'datasets' => [[
                'label' => 'Monthly Sales',
                'data' => array_values($monthlySales->toArray())
            ]]
        ]
    ]));

    return [
        'revenueByProduct' => $revenueChartUrl,
        'monthlySales' => $monthlyChartUrl
    ];
}
}