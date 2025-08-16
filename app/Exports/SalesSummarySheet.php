<?php
namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesSummarySheet implements FromArray, WithHeadings
{
    public function array(): array
    {
        $sales = Sale::all();

        $totalRevenue = $sales->sum('price');
        $totalItems = $sales->sum('quantity');
        $averagePrice = $sales->avg('price');

        $byProduct = $sales->groupBy('product')->map(function ($group) {
            return [
                'total_quantity' => $group->sum('quantity'),
                'total_revenue' => $group->sum('price'),
            ];
        });

        $byDate = $sales->groupBy('sale_date')->map(function ($group) {
            return [
                'total_quantity' => $group->sum('quantity'),
                'total_revenue' => $group->sum('price'),
            ];
        });

        $summary = [
            ['Total Revenue', $totalRevenue],
            ['Total Items Sold', $totalItems],
            ['Average Price', $averagePrice],
            [],
            ['Breakdown by Product'],
            ['Product', 'Total Quantity', 'Total Revenue'],
        ];

        foreach ($byProduct as $product => $data) {
            $summary[] = [$product, $data['total_quantity'], $data['total_revenue']];
        }

        $summary[] = [];
        $summary[] = ['Breakdown by Date'];
        $summary[] = ['Date', 'Total Quantity', 'Total Revenue'];

        foreach ($byDate as $date => $data) {
            $summary[] = [$date, $data['total_quantity'], $data['total_revenue']];
        }

        return $summary;
    }

    public function headings(): array
    {
        return [];
    }
}