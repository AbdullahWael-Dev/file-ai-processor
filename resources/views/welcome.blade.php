<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>عرض PDF</title>
    <style>
        @font-face {
            font-family: 'amiri';
            src: resource_path('/fonts/Amiri-Regular.ttf') format('truetype');
        }

        body {
            font-family: 'amiri', sans-serif;
            direction: rtl;
            text-align: right;
            padding: 20px;
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        h1 {
            color: #ffffff;
        }

        .pdf-container {
            margin-top: 20px;
            background-color: #2d2d2d;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #404040;
        }

        .btn-download {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn-download:hover {
            background-color: #0b5ed7;
        }

        iframe {
            background-color: #ffffff;
            border-radius: 4px;
        }

        .error-message {
            color: #ea868f;
            background-color: #842029;
            border: 1px solid #a52834;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<h1>عرض الملخص</h1>

<a id="downloadBtn" class="btn-download">تحميل PDF</a>

<div class="pdf-container">

    @if(session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif

    @if(isset($pdfBase64))
        <iframe
            id="pdfFrame"
            src="data:application/pdf;base64,{{ $pdfBase64 }}"
            width="100%"
            height="1000px"
            style="border: none;">
        </iframe>
    @endif
</div>

<script>
    document.getElementById('downloadBtn').addEventListener('click', function () {
        const pdfData = "{{ $pdfBase64 }}";
        const link = document.createElement('a');
        link.href = "data:application/pdf;base64," + pdfData;
        link.download = "ai_output.pdf";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
</script>
</body>
</html>
