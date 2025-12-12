@extends('auth.base')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-11 col-sm-12">
                <div class="card shadow border-0 rounded-4 overflow-hidden position-relative">
                    <a href="{{ route('home.page') }}"
                        class="btn btn-outline-primary position-absolute top-0 start-0 m-3 rounded d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; color: #4191E8;">
                        <x-arrow direction="left" size="24" />
                    </a>
                    <div class="row g-0 align-items-center">
                        <div
                            class="col-md-5 bg-white d-flex flex-column align-items-center justify-content-center p-4 text-center">
                            <img src="{{ asset('images/PreKantinLogo.png') }}" alt="PreKantin Logo" class="img-fluid mb-4"
                                style="max-width: 200px;">

                            <p class="small text-dark mb-0">
                                {{ __('login.already') }}
                                <a href="{{ route('register.page') }}" class="fw-bold text-decoration-none"
                                    style="color: #4191E8">{{ __('daftar') }}</a>
                            </p>
                        </div>

                        <div class="col-md-7 bg-white p-5">
                            <h3 class="text-center mb-4 fw-bold text-dark">{{ __('masuk') }}</h3>

                            <form action={{ route('login.do') }} method="POST" id="loginForm">
                                @csrf
                                <div class="mb-3">
                                    <input type="email"
                                        class="form-control form-control-lg border-0 text-white custom-input"
                                        placeholder="{{ __('register.email') }}" name="email" id="email"
                                        value="{{ old('email') }}"
                                        style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;" required>
                                    @error('email')
                                        <div class="small text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="position-relative">
                                        <input type="password"
                                            class="form-control form-control-lg border-0 text-white custom-input"
                                            placeholder="{{ __('register.password') }}" name="password" id="password"
                                            style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;"
                                            required>
                                    </div>
                                    @error('password')
                                        <div class="small text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-check mb-5">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" style="color: #4191E8" for="remember">
                                        {{ __('login.remember') }}
                                    </label>
                                </div>

                                <button type="submit" id="loginSubmit"
                                    class="btn btn-lg fw-semibold text-white border-0 w-75 mx-auto d-block"
                                    style="background-color: #FB8C30;">
                                    <span id="btnText">{{ __('masuk') }}</span>
                                    <span id="btnSpinner" class="spinner-border spinner-border-sm text-light d-none"
                                        role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                </button>

                                <div class="text-center mt-3">
                                    <a href={{ route('password.request') }}
                                        class="btn btn-dark text-white fw-bold small w-50">
                                        {{ __('login.lupa') }}
                                    </a>
                                </div>
                            </form>
                            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                                @if (session('success'))
                                    <div id="registerToast" class="toast align-items-center text-bg-success border-0"
                                        role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="d-flex">
                                            <div class="toast-body">
                                                {{ session('success') }}
                                            </div>
                                            <button type="button" class="btn-close btn-close me-2 m-auto"
                                                data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                                @if (session('status'))
                                    <div id="loginToast" class="toast align-items-center text-bg-success border-0"
                                        role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="d-flex">
                                            <div class="toast-body">
                                                {{ session('status') }}
                                            </div>
                                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            const password = document.getElementById("password");
            const toggle = document.getElementById("togglePassword");
            document.addEventListener('DOMContentLoaded', function() {
                var toastEl = document.getElementById('registerToast');
                if (toastEl) {
                    var toast = new bootstrap.Toast(toastEl, {
                        delay: 10000
                    });
                    toast.show();
                }
            });
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                var btn = document.getElementById('loginSubmit');
                var spinner = document.getElementById('btnSpinner');
                var btnText = document.getElementById('btnText');

                btn.disabled = true;
                btnText.classList.add('d-none');
                spinner.classList.remove('d-none');
            });
        </script>
    </div>
@endsection
