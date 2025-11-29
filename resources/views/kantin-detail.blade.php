@extends('base')

@section('content')
    <div style="background-color: #C9E2FE" class="container-fluid py-5">
        <div class="row justify-content-center mb-5">
            <div class="col-12 col-md-8 col-lg-6 mb-4">
                <div id="merchantCard" class="card border-0 shadow rounded-4 p-4 position-relative">
                    <div class="card-img-top rounded-top rounded-4 position-relative d-flex align-items-center justify-content-center"
                        style="height: 400px; object-fit: cover; background-color: {{ $imageExist ? 'transparent' : '#e0e0e0' }}">
                        @if ($imageExist)
                            <img src="{{ asset($merchant->image) }}" alt="Merchant Image"
                                style="width:100%; height:100%; object-fit:cover;" class="rounded-top rounded-4">
                        @else
                            <x-camera size="96" class="text-gray-500" />
                        @endif
                        @if ($isMerchant)
                            <div class="position-absolute top-0 end-0 m-4 d-flex align-items-center justify-content-center rounded-circle"
                                style="width:50px; height:50px; cursor:pointer; background-color: #4191E8">
                                <x-pencil color="white" />
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bold mb-0 me-2">{{ $merchant->user->name }}</h3>
                                @if ($isMerchant)
                                    <x-pencil color="black" />
                                @endif
                            </div>
                            <span class="badge {{ $isOpen ? 'bg-success' : 'bg-danger' }} px-3 py-2 fs-6">
                                {{ $isOpen ? 'BUKA' : 'TUTUP' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <p class="mb-0 me-2" style="color: #4191E8">
                                {{ $merchant->open ?? '00:00' }} - {{ $merchant->close ?? '00:00' }}
                            </p>
                            @if ($isMerchant)
                                <x-pencil color="black" size="20" />
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3 mb-4">
                <div id="orderCard" class="card border-0 shadow rounded-4 p-4 d-flex flex-column"
                    style="{{ !$isMerchant ? 'height: 450px;' : '' }}">
                    @if ($isMerchant)
                        <h3 class="fw-bold">Total Pesanan</h3>
                        <p class="fs-4 fw-bold" style="color: #4191E8">{{ $orderCount ?? 0 }}</p>
                        <hr>
                        <h3 class="fw-bold">Total Transaksi</h3>
                        <p class="fs-4 fw-bold" style="color: #4191E8">Rp.{{ number_format($total, 2, ',', '.') }}</p>
                        <hr>
                        <h3 class="fw-bold">Total Pembeli</h3>
                        <p class="fs-4 fw-bold" style="color: #4191E8">{{ $customerCount ?? 0 }}</p>

                        <button class="btn w-100 mt-2 fw-bold text-white" style="background-color: #4191E8">SIMPAN</button>
                    @else
                        <h1 class="fw-bold text-center mb-1">Pesanan Anda</h1>
                        <hr>
                        <div id="orderScroll" class="flex-grow-1 overflow-auto pe-1">
                            {{-- list order di sini --}}
                        </div>
                        <button class="btn w-100 text-white fw-bold mt-2" style="background-color: #FB8C30">PESAN</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="row mt-4 
            mx-2 px-2 
            mx-sm-3 px-sm-3 
            mx-md-4 px-md-4 
            mx-lg-5 px-lg-5">
            <div class="col-12">
                <div
                    class="row g-4 
                        row-cols-1
                        row-cols-sm-2 
                        row-cols-md-3
                        row-cols-lg-4
                        row-cols-xl-6">
                    @if ($isMerchant)
                        <div class="col">
                            <x-menu-card-kantin-detail add />
                        </div>
                    @endif
                    @if ($menus->isEmpty())
                        @unless (!$isMerchant)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-4 p-5 text-center d-flex align-items-center justify-content-center"
                                    style="background-color: #f8f9fa;">
                                    <h4 class="fw-bold text-muted mb-1">Belum ada menu tersedia</h4>
                                    <p class="text-secondary">Merchant belum menambahkan menu apapun.</p>
                                </div>
                            </div>
                        @endunless
                    @else
                        @foreach ($menus as $menu)
                            <div class="col">
                                <x-menu-card-kantin-detail name="{{ $menu->name }}" price="{{ $menu->price }}"
                                    image="{{ $menu->image }}" merchant="{{ $isMerchant }}"/>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const merchantCard = document.getElementById('merchantCard');
            const orderCard = document.getElementById('orderCard');

            @if (!$isMerchant)
                if (merchantCard && orderCard) {
                    orderCard.style.height = merchantCard.offsetHeight + "px";
                }
            @endif
        });
    </script>

    </div>
@endsection
