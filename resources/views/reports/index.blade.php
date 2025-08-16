@extends('layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Sales Dashboard</h4>
        </div>
        <form action="{{ route('reports.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
    @csrf
    <div class="form-row align-items-center">
        <div class="col-auto">
            <input type="file" name="csv_file" class="form-control-file" required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Upload CSV</button>
        </div>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger mt-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success mt-2">
            {{ session('success') }}
        </div>
    @endif
</form>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="thead-dark">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price ($)</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
@foreach($sales as $sale)
    <tr>
        <td>{{ $sale->product }}</td>
        <td>{{ $sale->quantity }}</td>
        <td>${{ number_format($sale->price, 2) }}</td>
        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }}</td>
        <td>
            <a href="{{ route('reports.edit', $sale->id) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('reports.destroy', $sale->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</button>
            </form>
        </td>
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
