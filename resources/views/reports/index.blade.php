@extends('layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Sales Dashboard</h4>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="thead-dark">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price ($)</th>
                    <th>Date</th>
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
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $sales->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
