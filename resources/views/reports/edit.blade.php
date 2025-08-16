@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-warning text-white">Edit Sale</div>
    <div class="card-body">
        <form action="{{ route('reports.update', $sale->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Product</label>
                <input type="text" name="product" class="form-control" value="{{ $sale->product }}" required>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ $sale->quantity }}" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ $sale->price }}" required>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="sale_date" class="form-control" value="{{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d') }}" required>
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection