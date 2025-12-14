@extends('auth.base')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-11 col-sm-12">
                <div class="card shadow border-0 rounded-4 overflow-hidden position-relative">
                    <a href="{{ route('login') }}"
                        class="btn btn-outline-primary position-absolute top-0 start-0 m-3 rounded d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; color: #4191E8;">
                        <x-arrow direction="left" size="24" />
                    </a>
                    <div class="row g-0 align-items-center">
                        <div
                            class="col-md-5 bg-white d-flex flex-column align-items-center justify-content-center p-4 text-center">
                            <img src="{{ asset('images/PreKantinLogo.png') }}" alt="PreKantin Logo" class="img-fluid mb-4"
                                style="max-width: 200px;">
                        </div>

                        <div class="col-md-7 bg-white p-5">

                            <h3 class="text-center mb-4 fw-bold text-dark" id="formTitle">{{ __('forgot.title') }}
                            </h3>
                            <p class="text-center m-5 w-50 mx-auto fw-semibold" style="color: #4191E8">
                                {{ __('forgot.desc') }}
                            </p>

                            <form action="{{ route('password.email') }}" method="POST" id="forgotPasswordForm">
                                @csrf
                                <div class="mb-5">
                                    <input type="email"
                                        class="form-control form-control-lg border-0 text-white custom-input"
                                        placeholder="{{ __('register.email') }}" name="email" id="email"
                                        value="{{ old('email') }}"
                                        style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;" required>
                                    @error('email')
                                        <div class="small text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                    @if (session('success'))
                                        <div class="small text-success fw-bold mt-1">{{ session('success') }}</div>
                                    @endif
                                </div>

                                <button type="submit" id="forgotSubmit"
                                    class="btn btn-lg fw-semibold text-white border-0 w-75 mx-auto d-block px-5"
                                    style="background-color: #FB8C30;">
                                    <span id="btnText">{{ __('forgot.send_code') }}</span>
                                    <span id="btnSpinner" class="spinner-border spinner-border-sm text-light d-none"
                                        role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
                var btn = document.getElementById('forgotSubmit');
                var spinner = document.getElementById('btnSpinner');
                var btnText = document.getElementById('btnText');

                btn.disabled = true;
                btnText.classList.add('d-none');
                spinner.classList.remove('d-none');
            });
        </script>
    </div>
@endsection
