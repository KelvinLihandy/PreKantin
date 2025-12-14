@extends('base')

@section('content')
    <div style="background-color: #C9E2FE" class="min-vh-100 pb-5">
        <div class="" style="background-color: #4191E8">
            <section class="container">
                <div class="row align-items-center mb-5">
                    <div class="col-md-6">
                        <h1 class="fw-bold text-white mb-3" style="font-size: 3rem;">{{ __('kantin.list.title') }}</h1>
                        <p class="text-white fs-5">{{ __('kantin.list.lead') }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <img src="{{ asset('images/kantinListImage.png') }}" alt="Kantin" class="img-fluid shadow mb-5"
                            style="max-width: 400px;">
                    </div>
                </div>
            </section>
        </div>
        <div class="container">

            <!-- Filter Section -->
            @if ($merchants->count() > 0)
                <div class="row mb-4 align-items-center">
                    <div class="col-auto">
                        <form method="GET" action="{{ route('kantin.list') }}" class="d-flex align-items-center gap-3"
                            id="sortForm">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle fw-bold" type="button" id="sortDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-funnel"></i> {{ __('harga') }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                    <li>
                                        <a class="dropdown-item {{ $sort === 'name' ? 'active' : '' }}"
                                            href="{{ route('kantin.list', ['sort' => 'name']) }}" onclick="showLoading()">
                                            {{ __('kantin.list.all') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ $sort === 'price_asc' ? 'active' : '' }}"
                                            href="{{ route('kantin.list', ['sort' => 'price_asc']) }}"
                                            onclick="showLoading()">
                                            {{ __('kantin.list.low') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item {{ $sort === 'price_desc' ? 'active' : '' }}"
                                            href="{{ route('kantin.list', ['sort' => 'price_desc']) }}"
                                            onclick="showLoading()">
                                            {{ __('kantin.list.high') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div id="loadingSpinner" class="spinner-border text-primary d-none"
                                style="width: 1.6rem; height: 1.6rem;">
                            </div>
                        </form>
                    </div>
                </div>
            @endif


            <!-- Merchant Cards Grid -->
            <div class="row g-4">
                @if ($merchants->isEmpty())
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center" style="background-color: #f8f9fa;">
                            <h4 class="fw-bold text-muted">{{ __('kantin.list.empty') }}</h4>
                        </div>
                    </div>
                @else
                    @foreach ($merchants as $merchant)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="{{ route('kantin.page', $merchant->merchant_id) }}"
                                style="text-decoration: none; color: inherit;">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition"
                                    style="cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 0.5rem 1rem rgba(0,0,0,0.15)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)';">

                                    <!-- Image -->
                                    <div style="height: 200px; overflow: hidden; background-color: #e0e0e0;">
                                        @if ($merchant->image)
                                            <img src="{{ $merchant->image_url }}" alt="{{ $merchant->user->name }}"
                                                class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <div class="d-flex justify-content-center align-items-center w-100 h-100"
                                                style="background-color: #e0e0e0;">
                                                <x-camera size="50" class="text-gray-500" />
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold mb-2" style="color: #FB8C30;">
                                            {{ $merchant->user->name }}</h5>
                                        <p class="card-text text-muted small mb-2">{{ __('kantin.list.count') }}
                                            {{ $merchant->orders_count }} {{ __('kantin.list.times') }}</p>

                                        <!-- Price Range -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            @php
                                                $menuItems = $merchant->menuItems()->get();
                                                $minPrice = $menuItems->min('price') ?? 0;
                                                $maxPrice = $menuItems->max('price') ?? 0;
                                            @endphp
                                            <small class="text-secondary">
                                                Mulai dari Rp. {{ number_format($minPrice, 0, ',', '.') }} - Rp.
                                                {{ number_format($maxPrice, 0, ',', '.') }}
                                            </small>
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="mt-3">
                                            @php
                                                $isOpen = false;
                                                if ($merchant->open && $merchant->close) {
                                                    try {
                                                        $now = \Carbon\Carbon::now();
                                                        $open = \Carbon\Carbon::createFromFormat(
                                                            'H:i:s',
                                                            $merchant->open,
                                                        );
                                                        $close = \Carbon\Carbon::createFromFormat(
                                                            'H:i:s',
                                                            $merchant->close,
                                                        );
                                                        $isOpen = $now->between($open, $close);
                                                    } catch (\Exception $e) {
                                                        $isOpen = false;
                                                    }
                                                }
                                            @endphp
                                            <span
                                                class="badge {{ $isOpen ? 'bg-success' : 'bg-danger' }} py-2 px-3 fw-bold">
                                                @if ($isOpen)
                                                    {{ __('buka') }}
                                                @else
                                                    {{ __('tutup') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Pagination -->
            @if ($merchants->count() > 0)
                <div class="row mt-5">
                    <div class="col-12">
                        <nav aria-label="Page navigation" class="d-flex justify-content-center">
                            <ul class="pagination">

                                {{-- Previous Page Link --}}
                                <li class="page-item {{ $merchants->onFirstPage() ? 'disabled' : '' }}"
                                    style="margin: 0 3px;">
                                    @if ($merchants->onFirstPage())
                                        <span class="page-link"
                                            style="background-color: #4191E8; color: white; border: none;">←</span>
                                    @else
                                        <a class="page-link"
                                            href="{{ $merchants->appends(['sort' => $sort])->previousPageUrl() }}"
                                            style="background-color: #4191E8; color: white; border: none;">←</a>
                                    @endif
                                </li>

                                {{-- Page Numbers --}}
                                @foreach ($merchants->getUrlRange(1, $merchants->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $merchants->currentPage() ? 'active' : '' }}"
                                        style="margin: 0 3px;">
                                        @if ($page == $merchants->currentPage())
                                            <span class="page-link"
                                                style="background-color: #4191E8; color: white; border: none;">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a class="page-link" href="{{ $url }}?sort={{ $sort }}"
                                                style="background-color: #4191E8; color: white; border: none;">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    </li>
                                @endforeach

                                {{-- Next Page Link --}}
                                <li class="page-item {{ $merchants->hasMorePages() ? '' : 'disabled' }}"
                                    style="margin: 0 3px;">
                                    @if ($merchants->hasMorePages())
                                        <a class="page-link"
                                            href="{{ $merchants->appends(['sort' => $sort])->nextPageUrl() }}"
                                            style="background-color: #4191E8; color: white; border: none;">→</a>
                                    @else
                                        <span class="page-link"
                                            style="background-color: #4191E8; color: white; border: none;">→</span>
                                    @endif
                                </li>

                            </ul>
                        </nav>
                    </div>
                </div>
            @endif


        </div>
    </div>
    <script>
        function showLoading() {
            document.getElementById('loadingSpinner').classList.remove('d-none');
        }
    </script>
@endsection
