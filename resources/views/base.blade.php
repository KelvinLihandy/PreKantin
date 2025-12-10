<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PreKantin | {{ __('header.cepat_praktis_lezat') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
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

        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }
    </style>
</head>

<body class="min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark py-3 sticky-top" style="background-color: #4191E8">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="{{ route('home.page') }}">
                <img src="{{ asset('images/HomeLogo.png') }}" alt="PreKantin" height="40" class="me-2">
                PreKantin
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon text-white"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-lg-center">

                    @guest
                        <li class="nav-item"><a class="nav-link text-white fw-bold"
                                href="{{ route('home.page') }}">{{ __('navbar.home') }}</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-bold"
                                href="#">{{ __('navbar.kantin') }}</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-bold"
                                href="{{ route('about.page') }}">{{ __('navbar.tentang') }}</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-bold"
                                href="{{ route('login') }}">{{ __('masuk') }}</a></li>
                    @endguest

                    @auth
                        @php
                            $order_count = $navbarData['order_count'];
                            $fullName = $navbarData['fullName'];
                            $displayName = $navbarData['displayName'];
                            $role = $navbarData['role'];
                        @endphp

                        <div class="d-none d-lg-flex align-items-center">
                            @if ($role === 'Mahasiswa')
                                <li class="nav-item"><a class="nav-link text-white fw-bold"
                                        href="{{ route('home.page') }}">{{ __('navbar.home') }}</a></li>
                                {{-- not implemented --}}
                                <li class="nav-item"><a class="nav-link text-white fw-bold"
                                        href="#">{{ __('navbar.kantin') }}</a></li>
                            @endif

                            @if ($role === 'Merchant')
                                <li class="nav-item"><a class="nav-link text-white fw-bold"
                                        href="{{ route('kantin.page', ['id' => $navbarData['id']]) }}">{{ __('navbar.kelola') }}</a>
                                </li>
                            @endif

                            <li class="nav-item"><a class="nav-link text-white fw-bold"
                                    href="{{ route('about.page') }}">{{ __('navbar.tentang') }}</a></li>
                            <div class="mx-4" style="width:2px;height:40px;background:white;"></div>
                            <li class="nav-item d-flex align-items-center gap-2">
                                <span class="text-danger fw-bold">{{ $order_count ?? 0 }}</span>
                                <a class="nav-link p-0" href="{{ $role === 'Mahasiswa' ? route('order.history') : route('merchant.order.history') }}">
                                    <x-history />
                                </a>
                            </li>
                            <li class="nav-item dropdown d-flex flex-column align-items-end fw-bold ms-3">
                                <p class="text-warning m-0">{{ $role }}</p>
                                <p class="text-white m-0">{{ $displayName }}</p>

                                <ul class="dropdown-menu dropdown-menu-end text-center">
                                    <li class="fw-bold px-2 py-1">{{ $fullName }}</li>
                                    <li><a class="dropdown-item fw-bold" style="color: #4191E8"
                                            href="{{ route('password.change') }}">{{ __('reset.title') }}</a></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button class="dropdown-item text-danger fw-bold"
                                                type="submit">{{ __('keluar') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </div>
                        <div class="d-lg-none">
                            @if ($role === 'Mahasiswa')
                                <li class="nav-item"><a class="nav-link text-white fw-bold"
                                        href="{{ route('home.page') }}">{{ __('navbar.home') }}</a></li>
                                {{-- not implemented --}}
                                <li class="nav-item"><a class="nav-link text-white fw-bold"
                                        href="#">{{ __('navbar.kantin') }}</a></li>
                            @endif

                            @if ($role === 'Merchant')
                                <li class="nav-item"><a class="nav-link text-white fw-bold"
                                        href="{{ route('kantin.page', ['id' => $navbarData['id']]) }}">{{ __('navbar.kelola') }}</a>
                                </li>
                            @endif

                            <li class="nav-item"><a class="nav-link text-white fw-bold"
                                    href="{{ route('about.page') }}">{{ __('navbar.tentang') }}</a></li>

                            <div class="d-flex gap-4 my-2">
                                <li class="nav-item fw-bold">
                                    <span class="text-warning d-block">{{ $role }}</span>
                                    <span class="text-white d-block">{{ $fullName }}</span>
                                </li>
                                <li class="nav-item d-flex align-items-center gap-2">
                                    <span class="text-danger fw-bold">{{ $order_count ?? 0 }}</span>
                                    <a class="nav-link p-0 text-white fw-bold"
                                        href="{{ $role === 'Mahasiswa' ? route('order.history') : route('merchant.order.history') }}">
                                        <x-history />
                                    </a>
                                </li>
                            </div>
                            <li class="nav-item"><a class="nav-link text-white fw-bold"
                                    href="{{ route('password.change') }}">{{ __('reset.title') }}</a></li>
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="nav-link text-white fw-bold border-0 bg-transparent p-0"
                                        type="submit">{{ __('keluar') }}</button>
                                </form>
                            </li>
                        </div>
                    @endauth

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
