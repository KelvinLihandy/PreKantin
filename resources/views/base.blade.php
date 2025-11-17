<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PreKantin | {{ __('header.cepat_praktis_lezat') }}</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
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

        #roleTab .nav-link.active {
            color: #4191E8 !important;
        }

        #roleTab .nav-link:not(.active) {
            color: rgba(0, 0, 0, 0.5) !important;
        }
    </style>
</head>

<body class="min-vh-100">
    {{-- change password ada di dropdown hover ketika navbar auth --}}
    <nav class="navbar navbar-expand-lg navbar-dark py-3" style="background-color: #4191E8">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#">
                <img src="{{ asset('images/HomeLogo.png') }}" alt="PreKantin" height="40" class="me-2">
                PreKantin
            </a>
            <button class="navbar-toggler navbar-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon text-white"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="{{ route('home.page') }}">{{ __('navbar.home') }}</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="#">{{ __('navbar.kantin') }}</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="{{ route('about.page') }}">{{ __('navbar.tentang') }}</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="{{ route('login.page') }}">{{ __('masuk') }}</a></li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row align-items-center text-center text-md-start">
                <div class="col-md-4 mb-4 mb-md-0 d-flex flex-column align-items-center align-items-md-start">
                    <img src="{{ asset('images/HomeLogo.png') }}" alt="PreKantin" class="img-fluid mb-2"
                        style="max-height: 60px;">
                    <h1 class="fw-bold mb-2" style="color: #FB8C30">PreKantin</h1>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                    @foreach (__('footer.cepat_praktis_lezat') as $line)
                        <p class="fs-3 fw-bold text-center mb-0">{{ $line }}</p>
                    @endforeach

                </div>

                <div class="col-md-4 d-flex flex-column align-items-center align-items-md-end">
                    <p class="fw-bold mb-3">{{ __('footer.hubungi') }}</p>

                    <div class="d-flex align-items-center mb-2">
                        <x-phone class="me-2" />
                        <span>(+62) 0821 2571 4778</span>
                    </div>

                    <div class="d-flex align-items-center mb-2">
                        <x-location class="me-2" />
                        <span>{{ __('footer.alamat') }}</span>
                    </div>

                    <div class="d-flex align-items-center mb-2">
                        <x-email class="me-2" />
                        <span>frandy.s@prekantin.com</span>
                    </div>

                    <p class="small mt-3 fw-bold" style="color:#4191E8;">
                        @ 2025 PreKantin All Rights Reserved
                    </p>
                </div>

            </div>
        </div>
    </footer>


    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
