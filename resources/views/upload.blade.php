<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>رفع ملف للتلخيص أو التنسيق</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        .container {
            background-color: #1a1a1a;
        }

        h2 {
            color: #ffffff;
        }

        .bg-white {
            background-color: #2d2d2d !important;
            border: 1px solid #404040;
        }

        .form-label {
            color: #e0e0e0;
        }

        .form-control {
            background-color: #252525;
            border: 1px solid #404040;
            color: #e0e0e0;
        }

        .form-control:focus {
            background-color: #2d2d2d;
            border-color: #0d6efd;
            color: #e0e0e0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .form-control::file-selector-button {
            background-color: #404040;
            border: none;
            color: #e0e0e0;
            padding: 8px 15px;
            margin-left: 10px;
        }

        .form-control::file-selector-button:hover {
            background-color: #505050;
        }

        .action-btn {
            width: 48%;
            font-size: 1.1rem;
            padding: 12px 0;
            margin: 0 1% 10px 1%;
        }

        .file-input {
            height: 45px;
        }

        .btn-outline-primary {
            color: #6ea8fe;
            border-color: #6ea8fe;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
        }

        .btn-outline-success {
            color: #75b798;
            border-color: #75b798;
        }

        .btn-outline-success:hover {
            background-color: #198754;
            border-color: #198754;
            color: #ffffff;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .alert-danger {
            background-color: #842029;
            border-color: #a52834;
            color: #ea868f;
        }

        .alert-success {
            background-color: #0f5132;
            border-color: #198754;
            color: #75b798;
        }

        .alert-success h5 {
            color: #75b798;
        }

        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.5) !important;
        }
    </style>
</head>
<body>
<div class="container py-5" style="max-width: 1200px;">
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <h2 class="mb-4 text-center">رفع ملف للتلخيص أو التنسيق</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
        $content = session('result') ?? '';
        function isArabic($string) {
            return preg_match('/[\x{0600}-\x{06FF}]/u', $string);
        }
        $dir = isArabic($content) ? 'rtl' : 'ltr';
    @endphp

    @if(session('result'))
        <div class="alert alert-success">
            <h5>النتيجة:</h5>
            <div dir="{{ $dir }}" style="white-space: pre-wrap; font-family: Arial, sans-serif; line-height: 1.6; color: #e0e0e0;">
                {!! nl2br(e(session('result'))) !!}
            </div>
        </div>
    @endif

    <form action="{{ route('file.process') }}" method="POST" enctype="multipart/form-data" id="fileForm" class="bg-white p-4 rounded shadow-sm">
        @csrf
        <div class="mb-4">
            <label for="file" class="form-label">اختر ملف (PDF, DOCX, TXT)</label>
            <input type="file" name="file" id="file" class="form-control file-input" required accept=".pdf,.docx,.txt" />
        </div>

        <input type="hidden" name="action" id="actionInput" required />

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-primary action-btn" onclick="submitForm('summarize')">تلخيص الملف</button>
            <button type="button" class="btn btn-outline-success action-btn" onclick="submitForm('format')">تنسيق الملف</button>
        </div>
    </form>

</div>

<script>
    function submitForm(action) {
        const fileInput = document.getElementById('file');
        if (!fileInput.value) {
            alert('يرجى اختيار ملف أولاً');
            return;
        }
        document.getElementById('actionInput').value = action;
        document.getElementById('fileForm').submit();
    }
</script>
</body>
</html>
