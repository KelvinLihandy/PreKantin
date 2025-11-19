@extends('base')

@section('content')
    <div>
        <section class="text-white py-5" style="background-color: #4191E8">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="fw-bold mb-3 display-1">
                            {{ __('home.boom') }}
                        </p>
                        <p class="lead mb-4">{{ __('home.lead') }}</p>
                        <a href="{{ route('register.page', ['tab' => 'mahasiswa']) }}"
                            class="btn btn-md fw-bold me-2 text-white py-3 px-3"
                            style="background-color: #FB8C30; border-radius: 1rem">{{ __('home.order_button') }}</a>
                        <a href="{{ route('register.page', ['tab' => 'merchant']) }}"
                            class="btn btn-dark btn-md fw-bold py-3 px-3"
                            style="border-radius: 1rem">{{ __('home.merchant_button') }}</a>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('images/HomePic.png') }}" class="img-fluid rounded shadow mt-4 mt-md-0"
                            alt="Kantin">
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-light py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold">{{ __('home.paling_banyak_dipesan') }}</h3>
                    {{-- not implemented --}}
                    <a href="#" class="fw-bold btn"
                        style="color: #4191E8; border: 2px solid #FB8C30; background-color: transparent; text-decoration: none; rounded"
                        onmouseover="this.style.backgroundColor='#FB8C30'; this.style.color='white'; this.style.borderColor='#FB8C30';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#4191E8'; this.style.borderColor='#FB8C30';">
                        {{ __('home.menu.more') }}
                    </a>

                </div>
                <div class="row g-4">
                    @foreach ($topMenuItems as $topMenuItem)
                        {{-- fav not implemented --}}
                        <x-fav-menu-card image="{{ $topMenuItem->menu_item->image }}"
                            name="{{ $topMenuItem->menu_item->name }}"
                            merchant="{{ $topMenuItem->menu_item->merchant->user->name }}"
                            price="{{ $topMenuItem->menu_item->price }}" />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="text-white py-5 text-center" style="background-color: #4191E8">
            <div class="container">
                <p class="fs-1 fw-bold mb-4">{{ __('home.why') }}</p>
                <ul class="nav nav-pills justify-content-center mb-4" id="roleTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold px-4 py-2 shadow-sm bg-white" style="border-radius: 0.5rem;"
                            id="mahasiswa-tab" data-bs-toggle="pill" data-bs-target="#mahasiswa" type="button"
                            role="tab" aria-controls="mahasiswa" aria-selected="true">
                            {{ __('mahasiswa') }}
                        </button>
                    </li>
                    <li class="nav-item ms-2" role="presentation">
                        <button class="nav-link fw-bold px-4 py-2 shadow-sm bg-white" style="border-radius: 0.5rem;"
                            id="merchant-tab" data-bs-toggle="pill" data-bs-target="#merchant" type="button" role="tab"
                            aria-controls="merchant" aria-selected="false">
                            {{ __('merchant') }}
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="roleTabContent">
                    <div class="tab-pane fade show active" id="mahasiswa" role="tabpanel" aria-labelledby="mahasiswa-tab">
                        <div class="row justify-content-center align-items-center g-4">
                            <div class="col-md-6">
                                <div class="bg-white text-dark p-4 rounded rounded-4 shadow-sm text-center">
                                    <h5 class="fw-bold" style="color: #FB8C30">
                                        {{ __('home.why.mahasiswa.title') }}
                                    </h5>
                                    <p class="mb-0 mt-2">
                                        {{ __('home.why.mahasiswa.desc') }}
                                        <strong>
                                            {{ __('home.why.mahasiswa.desc.strong') }}
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <img src="{{ asset('images/home.png') }}" alt="Mahasiswa di kantin"
                                    class="img-fluid rounded-4 shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="merchant" role="tabpanel" aria-labelledby="merchant-tab">
                        <div class="row justify-content-center align-items-center g-4 rounded rounded-3">
                            <div class="col-md-6">
                                <div class="bg-white text-dark p-4 rounded rounded-4 shadow-sm text-center">
                                    <h5 class="fw-bold" style="color: #FB8C30">
                                        {{ __('home.why.merchant.title') }}
                                    </h5>
                                    <p class="mb-0 mt-2">
                                        {{ __('home.why.merchant.desc') }}
                                        <strong>
                                            {{ _('home.why.merchant.desc.strong') }}
                                        </strong>
                                        {{ __('home.why.merchant.desc.continue') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <img src="{{ asset('images/adasd.png') }}" alt="Merchant PreKantin"
                                    class="img-fluid rounded-4 shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            @if (session('success'))
                <div id="loginToast" class="toast align-items-center text-bg-success border-0" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var toastEl = document.getElementById('loginToast');
                if (toastEl) {
                    var toast = new bootstrap.Toast(toastEl, {
                        delay: 10000
                    });
                    toast.show();
                }
            });
        </script>
    </div>
@endsection
