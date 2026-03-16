<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 30px;
            background-color: #1a1a1a;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: {{ $direction }};
            text-align: {{ $textAlign }};
            font-size: 14px;
            line-height: 1.6;
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
            color: #ffffff;
        }
        .content {
            white-space: pre-wrap;
            word-wrap: break-word;
            background-color: #2d2d2d;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #404040;
            color: #e0e0e0;
        }
        .section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<h1>نتيجة المعالجة</h1>
<div class="content">
    {!! nl2br(e($result)) !!}
</div>
</body>
</html>
