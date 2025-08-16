<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesExport implements FromCollection, WithHeadings, WithMultipleSheets
{
public function collection()
{
return Sale::select('product', 'quantity', 'price', 'sale_date')->get();
}

public function headings(): array
{
return ['Product', 'Quantity', 'Price', 'Sale Date'];
}

public function sheets(): array
    {
        return [
            new SalesRawSheet(),
            new SalesSummarySheet(),
        ];
    }
}
