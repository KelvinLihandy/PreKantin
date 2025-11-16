@extends('auth.base')
@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100" style="background-color: black">
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

                                <x-login-tabs :items="[
                                    ['id' => 'student', 'label' => __('mahasiswa'), 'active' => true],
                                    ['id' => 'merchant', 'label' => __('merchant'), 'active' => false],
                                ]" />

                                <img src="{{ asset('images/PreKantinLogo.png') }}" alt="PreKantin Logo"
                                    class="img-fluid mt-5 mb-4" style="max-width: 200px;">

                                <p class="small text-dark mb-0">
                                    {{ __('register.already') }}
                                    <a href="{{ route('login.page') }}"
                                        class="text-primary fw-bold text-decoration-none">{{ __('register.log_in') }}</a>
                                </p>
                            </div>

                            <div class="col-md-7 bg-white p-5">

                                <h3 class="text-center mb-4 fw-bold text-dark" id="formTitle">{{ __('register.mahasiswa') }}
                                </h3>

                                <form action="{{ route('register.do') }}" method="POST" id="registerForm">
                                    @csrf

                                    <input type="hidden" name="role" id="roleInput" value="mahasiswa">

                                    <div class="mb-3">
                                        <input type="text"
                                            class="form-control form-control-lg border-0 text-white custom-input"
                                            placeholder="{{ __('register.mahasiswa.nama') }}" name="name" id="name"
                                            value="{{ old('name') }}"
                                            style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;"
                                            required>
                                        @error('name')
                                            <div class="small text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <input type="email"
                                            class="form-control form-control-lg border-0 text-white custom-input"
                                            placeholder="{{ __('register.email') }}" name="email" id="email"
                                            value="{{ old('email') }}"
                                            style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;"
                                            required>
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
                                            <span id="togglePassword"
                                                class="position-absolute top-50 end-0 translate-middle-y me-3"
                                                style="cursor: pointer;">
                                                <x-eye-closed />
                                            </span>
                                        </div>
                                        @error('password')
                                            <div class="small text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <span id="iconOpen" class="d-none">
                                        <x-eye />
                                    </span>

                                    <span id="iconClosed" class="d-none">
                                        <x-eye-closed />
                                    </span>

                                    <div class="mb-5">
                                        <input type="text"
                                            class="form-control form-control-lg border-0 text-white custom-input"
                                            placeholder="{{ __('register.confirm') }}" name="confirmation" id="confirmation"
                                            style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;"
                                            required>
                                        @error('confirmation')
                                            <div class="small text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                        class="btn btn-lg fw-semibold text-white border-0 w-75 mx-auto d-block px-5"
                                        style="background-color: #FB8C30;">
                                        {{ __('register.register') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const labels = {
                mahasiswa: "{{ __('register.mahasiswa') }}",
                mahasiswaNama: "{{ __('register.mahasiswa.nama') }}",
                merchant: "{{ __('register.merchant') }}",
                merchantNama: "{{ __('register.merchant.nama') }}"
            };
            const tabs = document.querySelectorAll('.nav-link');
            const roleInput = document.getElementById('roleInput');
            const formTitle = document.getElementById('formTitle');
            const nameField = document.getElementById('name');
            const password = document.getElementById("password");
            const toggle = document.getElementById("togglePassword");
            const iconOpen = document.getElementById("iconOpen").innerHTML;
            const iconClosed = document.getElementById("iconClosed").innerHTML;


            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    if (this.id === 'merchant-tab') {
                        roleInput.value = 'merchant';
                        formTitle.textContent = labels.merchant;
                        nameField.placeholder = labels.merchantNama;
                    } else {
                        roleInput.value = 'mahasiswa';
                        formTitle.textContent = labels.mahasiswa;
                        nameField.placeholder = labels.mahasiswaNama;
                    }
                });
            });

            toggle.addEventListener("click", () => {
                const isHidden = password.type === "password";

                password.type = isHidden ? "text" : "password";
                toggle.innerHTML = isHidden ? iconOpen : iconClosed;
            });
        </script>

        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </div>
@endsection
