<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background: #f2f2f2; }
        .summary { margin-bottom: 20px; }
        .chart { margin: 20px 0; text-align: center; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Sales Report</h2>
    <div class="summary">
        <strong>Total Revenue:</strong> ${{ number_format($totalRevenue, 2) }}<br>
        <strong>Total Items Sold:</strong> {{ $totalItems }}
    </div>
    <div class="chart">
        <strong>Revenue by Product</strong><br>
        @if($chartImages['revenueByProduct'])
            <img src="{{ $chartImages['revenueByProduct'] }}" alt="Revenue by Product" style="max-width:500px;">
        @else
            <em>[Chart image here]</em>
        @endif
    </div>
    <div class="chart">
        <strong>Monthly Sales Trend</strong><br>
        @if($chartImages['monthlySales'])
            <img src="{{ $chartImages['monthlySales'] }}" alt="Monthly Sales Trend" style="max-width:500px;">
        @else
            <em>[Chart image here]</em>
        @endif
    </div>
    <table>
        <thead>
        <tr>
            <th>Product</th><th>Quantity</th><th>Price</th><th>Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->product }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>${{ number_format($sale->price, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>