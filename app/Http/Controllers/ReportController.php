<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}