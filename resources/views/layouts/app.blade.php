<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report Generator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 4.6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
            letter-spacing: 1px;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <a class="navbar-brand" href="{{ route('reports.index') }}">Sales Reports</a>
        <div class="ml-auto">
            <a href="{{ route('reports.excel') }}" class="btn btn-success btn-sm mr-2">Export Excel</a>
            <a href="{{ route('reports.pdf') }}" class="btn btn-danger btn-sm">Export PDF</a>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
