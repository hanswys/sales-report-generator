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
<div class="d-flex justify-content-center">
    {{ $sales->links('pagination::bootstrap-4') }}
</div>