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
        <form id="filter-form" class="form-inline mb-3">
            <input type="text" name="product" class="form-control mr-2" placeholder="Product" value="{{ request('product') }}">
            <input type="date" name="start_date" class="form-control mr-2" value="{{ request('start_date') }}">
            <input type="date" name="end_date" class="form-control mr-2" value="{{ request('end_date') }}">
            <input type="number" name="min_quantity" class="form-control mr-2" placeholder="Min Quantity" value="{{ request('min_quantity') }}">
            <input type="number" name="max_quantity" class="form-control mr-2" placeholder="Max Quantity" value="{{ request('max_quantity') }}">
            <input type="number" name="min_price" class="form-control mr-2" placeholder="Min Price" value="{{ request('min_price') }}">
            <input type="number" name="max_price" class="form-control mr-2" placeholder="Max Price" value="{{ request('max_price') }}">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        <div class="card-body p-0" id="sales-table-container">
            @include('reports.partials.sales_table', ['sales' => $sales])
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).on('submit', '#filter-form', function(e) {
    e.preventDefault();
    $.ajax({
        url: "{{ route('reports.index') }}",
        type: "GET",
        data: $(this).serialize(),
        success: function(data) {
            $('#sales-table-container').html(data);
        }
    });
});

$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, $('#filter-form').serialize(), function(data) {
        $('#sales-table-container').html(data);
    });
});
</script>
@endpush
