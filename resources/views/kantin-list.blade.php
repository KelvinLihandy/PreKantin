@extends('base')

@section('content')
    <div style="background-color: #C9E2FE" class="min-vh-100 py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <h1 class="fw-bold text-white mb-3" style="font-size: 3rem;">Kantin-Kantin Kami</h1>
                    <p class="text-white fs-5">Pesan sekarang dan nikmati makanan segar dari berbagai kantin pilihan!</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/dummyKantin.png') }}" alt="Kantin" class="img-fluid rounded-4 shadow" style="max-width: 400px;">
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row mb-4 align-items-center">
                <div class="col-auto">
                    <form method="GET" action="{{ route('kantin.list') }}" class="d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle fw-bold" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-funnel"></i> Harga
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item {{ $sort === 'name' ? 'active' : '' }}" href="{{ route('kantin.list', ['sort' => 'name']) }}">Semua Kantin</a></li>
                                <li><a class="dropdown-item {{ $sort === 'price_asc' ? 'active' : '' }}" href="{{ route('kantin.list', ['sort' => 'price_asc']) }}">Harga Terendah</a></li>
                                <li><a class="dropdown-item {{ $sort === 'price_desc' ? 'active' : '' }}" href="{{ route('kantin.list', ['sort' => 'price_desc']) }}">Harga Tertinggi</a></li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Merchant Cards Grid -->
            <div class="row g-4">
                @if ($merchants->isEmpty())
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center" style="background-color: #f8f9fa;">
                            <h4 class="fw-bold text-muted">Tidak ada kantin tersedia</h4>
                        </div>
                    </div>
                @else
                    @foreach ($merchants as $merchant)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="{{ route('kantin.page', $merchant->merchant_id) }}" style="text-decoration: none; color: inherit;">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition" 
                                    style="cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 0.5rem 1rem rgba(0,0,0,0.15)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)';">
                                    
                                    <!-- Image -->
                                    <div style="height: 200px; overflow: hidden; background-color: #e0e0e0;">
                                        @if ($merchant->image && file_exists(public_path($merchant->image)))
                                            <img src="{{ asset($merchant->image) }}" alt="{{ $merchant->user->name }}" 
                                                class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/dummyKantin.png') }}" alt="{{ $merchant->user->name }}" 
                                                class="w-100 h-100" style="object-fit: cover;">
                                        @endif
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold mb-2" style="color: #FB8C30;">{{ $merchant->user->name }}</h5>
                                        <p class="card-text text-muted small mb-2">Telah dipesan 1000 kali</p>
                                        
                                        <!-- Price Range -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            @php
                                                $menuItems = $merchant->menuItems()->get();
                                                $minPrice = $menuItems->min('price') ?? 0;
                                                $maxPrice = $menuItems->max('price') ?? 0;
                                            @endphp
                                            <small class="text-secondary">
                                                Mulai dari Rp. {{ number_format($minPrice, 0, ',', '.') }} - Rp. {{ number_format($maxPrice, 0, ',', '.') }}
                                            </small>
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="mt-3">
                                            @php
                                                $isOpen = false;
                                                if ($merchant->open && $merchant->close) {
                                                    try {
                                                        $now = \Carbon\Carbon::now();
                                                        $open = \Carbon\Carbon::createFromFormat('H:i:s', $merchant->open);
                                                        $close = \Carbon\Carbon::createFromFormat('H:i:s', $merchant->close);
                                                        $isOpen = $now->between($open, $close);
                                                    } catch (\Exception $e) {
                                                        $isOpen = false;
                                                    }
                                                }
                                            @endphp
                                            <span class="badge {{ $isOpen ? 'bg-success' : 'bg-danger' }} py-2 px-3 fw-bold">
                                                {{ $isOpen ? 'BUKA' : 'TUTUP' }}
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
            @if ($merchants->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        <nav aria-label="Page navigation" class="d-flex justify-content-center">
                            <ul class="pagination">
                                <!-- Previous Page Link -->
                                @if ($merchants->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link" style="background-color: #4191E8; color: white; border: none;">←</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $merchants->previousPageUrl() }}" style="background-color: #4191E8; color: white; border: none;">←</a>
                                    </li>
                                @endif

                                <!-- Pagination Elements -->
                                @foreach ($merchants->getUrlRange(1, $merchants->lastPage()) as $page => $url)
                                    @if ($page == $merchants->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link" style="background-color: #4191E8; color: white; border: none;">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}" style="background-color: #4191E8; color: white; border: none; margin: 0 2px;">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                <!-- Next Page Link -->
                                @if ($merchants->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $merchants->nextPageUrl() }}" style="background-color: #4191E8; color: white; border: none;">→</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link" style="background-color: #4191E8; color: white; border: none;">→</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection