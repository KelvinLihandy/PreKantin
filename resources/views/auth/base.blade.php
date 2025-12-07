<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PreKantin | {{ __('header.cepat_praktis_lezat') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, 'Segoe UI', sans-serif;
        }

        input.custom-input::placeholder,
        input.custom-input:focus::placeholder {
            color: rgba(255, 255, 255, 0.7) !important;
        }
    </style>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100" style="background-color: black">
        @yield('content')
    </div>
</body>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</html>
