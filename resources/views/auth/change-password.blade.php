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
                        </div>

                        <div class="col-md-7 bg-white p-5">

                            <h3 class="text-center mb-4 fw-bold text-dark" id="formTitle">{{ __('reset.title') }}
                            </h3>

                            <form action="{{ route('password.changed') }}" method="POST" id="changeForm">
                                @csrf
                                <div class="mb-3">
                                    <div class="position-relative">
                                        <input type="password"
                                            class="form-control form-control-lg border-0 text-white custom-input"
                                            placeholder="{{ __('change.password') }}" name="password" id="password"
                                            style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;"
                                            required>
                                    </div>
                                    @error('password')
                                        <div class="small text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <input type="text"
                                        class="form-control form-control-lg border-0 text-white custom-input"
                                        placeholder="{{ __('change.new_pass') }}" name="new_password" id="new_password"
                                        style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;" required>
                                    @error('new_password')
                                        <div class="small text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-5">
                                    <input type="text"
                                        class="form-control form-control-lg border-0 text-white custom-input"
                                        placeholder="{{ __('register.confirm') }}" name="confirmation" id="confirmation"
                                        style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;" required>
                                    @error('confirmation')
                                        <div class="small text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" id="changeSubmit"
                                    class="btn btn-lg fw-bold text-white border-0 w-75 mx-auto d-block px-5"
                                    style="background-color: #FB8C30;">
                                    <span id="btnText">{{ __('reset.reset') }}</span>
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
            document.getElementById('changeForm').addEventListener('submit', function(e) {
                var btn = document.getElementById('changeSubmit');
                var spinner = document.getElementById('btnSpinner');
                var btnText = document.getElementById('btnText');

                btn.disabled = true;
                btnText.classList.add('d-none');
                spinner.classList.remove('d-none');
            });
        </script>
    </div>
@endsection
