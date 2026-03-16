<!DOCTYPE html>
<html lang="ar" dir="{{ $direction }}">
<head>
    <meta charset="UTF-8">
    <title>نتيجة المعالجة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'amiri', DejaVu Sans, sans-serif;
            direction: {{ $direction }};
            text-align: {{ $textAlign }};
            line-height: 1.8;
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        .container {
            background-color: #1a1a1a;
        }

        .card {
            background-color: #2d2d2d;
            border: 1px solid #404040;
            color: #e0e0e0;
        }

        pre {
            background-color: #252525;
            color: #e0e0e0;
            border: 1px solid #404040;
            padding: 15px;
            border-radius: 5px;
            margin: 0;
        }

        p {
            font-size: 16px;
            color: #e0e0e0;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
    </style>
</head>
<body class="container py-5">

<div class="container">
    <div class="card p-3 mb-3">
        <pre>{{ $result }}</pre>
    </div>

    <a href="{{ route('download.pdf') }}" target="_blank" class="btn btn-danger">📄 عرض PDF</a>
    <a href="{{ route('download.word') }}" class="btn btn-primary">📝 تحميل Word</a>
</div>

</body>
</html>
