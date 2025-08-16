<?php
namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesRawSheet implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sale::select('product', 'quantity', 'price', 'sale_date')->get();
    }

    public function headings(): array
    {
        return ['Product', 'Quantity', 'Price', 'Sale Date'];
    }
}