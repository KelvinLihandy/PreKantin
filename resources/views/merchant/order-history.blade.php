@extends('base')

@section('content')
    <div class="container-fluid min-vh-100 py-5" style="background-color: #C9E2FE;">
        <div class="container">

            <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
                <h2 class="fw-bold m-0">Order History Merchant</h2>
            </div>

            <h3 class="fw-bold text-primary mb-3 mt-5 px-2">Your Orders</h3>

            @if($activeOrders->isEmpty())
                <div class="card rounded-4 border-0 p-4 text-center shadow-sm bg-white">
                    <p class="m-0 text-muted">Belum ada pesanan masuk saat ini.</p>
                </div>
            @else
                @foreach ($activeOrders as $order)
                    <div class="card border-primary border-2 shadow-sm rounded-4 mb-4" style="border-color: #4191E8 !important;">

                        <div class="card-body p-4">
                            <div class="row h-100 align-items-stretch">

                                <div class="col-md-8 d-flex flex-column justify-content-between">
                                    <div>
                                        <h5 class="fw-bold mb-3" style="color: #4191E8">#{{ $order->order_id }}</h5>
                                        <p class="mb-1 fw-bold">Total Harga : Rp.
                                            {{ number_format($order->total_price, 0, ',', '.') }}
                                        </p>
                                        <p class="mb-1 text-muted">Buyer : {{ $order->user->name }}</p>
                                        <p class="mb-1 text-muted">Time : {{ $order->order_time->format('d F H:i') }}</p>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mt-3 align-items-center">
                                        <a href="{{ route('order.detail', $order->order_id) }}" class="btn text-white fw-bold px-4"
                                            style="background-color: #4191E8;">
                                            Lihat Detail
                                        </a>

                                        @if($order->status_id == 1)
                                            <form action="{{ route('merchant.order.update', $order->order_id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_id" value="2">
                                                <button class="btn btn-outline-primary fw-bold px-3">Terima</button>
                                            </form>
                                            <form action="{{ route('merchant.order.update', $order->order_id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_id" value="5">
                                                <button class="btn btn-danger fw-bold px-3">Tolak</button>
                                            </form>
                                        @endif

                                        @if($order->status_id == 2)
                                            <form action="{{ route('merchant.order.update', $order->order_id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_id" value="3">
                                                <button class="btn btn-warning text-dark fw-bold px-3">Siapkan</button>
                                            </form>
                                            <form action="{{ route('merchant.order.update', $order->order_id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_id" value="4">
                                                <button class="btn btn-success fw-bold px-3">Selesai</button>
                                            </form>
                                        @endif

                                        @if($order->status_id == 3)
                                            <form action="{{ route('merchant.order.update', $order->order_id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_id" value="4">
                                                <button class="btn btn-success fw-bold px-3">Pesanan Selesai</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 d-flex justify-content-md-end align-items-center mt-3 mt-md-0">
                                    @if($order->orderItems->isNotEmpty())
                                        @php $firstItem = $order->orderItems->first(); @endphp
                                        <div class="card border border-2 border-dark rounded-4 overflow-hidden bg-white shadow-sm"
                                            style="width: 220px;">
                                            <div style="height: 120px; overflow: hidden;">
                                                <img src="{{ asset($firstItem->menu_item->image) }}" class="w-100 h-100"
                                                    style="object-fit: cover;">
                                            </div>
                                            <div class="card-body p-2 d-flex flex-column justify-content-between"
                                                style="min-height: 110px;">
                                                <h6 class="fw-bold mb-2 text-start"
                                                    style="color: #FB8C30; font-size: 0.9rem; line-height: 1.2;
                                                                                                       display: -webkit-box; -webkit-line-clamp: 2;
                                                                                                       -webkit-box-orient: vertical; overflow: hidden;">
                                                    {{ $firstItem->menu_item->name }}
                                                </h6>
                                                <div class="d-flex justify-content-between align-items-end mt-auto">
                                                    <div class="px-2 py-1 rounded-3 text-white fw-bold"
                                                        style="background-color: #4191E8; font-size: 0.75rem;">
                                                        Rp. {{ number_format($firstItem->menu_item->price, 0, ',', '.') }}
                                                    </div>
                                                    <div class="d-flex align-items-baseline">
                                                        <span class="fw-bold me-1 fs-5 text-dark">&times;</span>
                                                        <span class="fw-bold"
                                                            style="color: #4191E8; font-size: 2rem; line-height: 0.8;">
                                                            {{ $firstItem->quantity }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <hr class="my-5 border-3 text-primary opacity-25">

            <h3 class="fw-bold text-primary mb-3 px-2">Riwayat Transaksi</h3>

            @foreach ($groupedHistory as $year => $months)
                <div class="mb-2">
                    <button class="btn w-100 text-start fw-bold fs-3 text-primary p-0 mb-2 d-flex align-items-center"
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseYear-{{ $year }}">
                        <span class="me-2">▼</span> {{ $year }}
                    </button>

                    <div class="collapse show" id="collapseYear-{{ $year }}">
                        @foreach ($months as $month => $orders)
                            <div class="ms-md-4 mb-4">
                                @php $monthId = $year . '-' . str_replace(' ', '', $month); @endphp
                                <button class="btn w-100 text-start fw-bold fs-4 text-primary p-0 mb-3 d-flex align-items-center"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseMonth-{{ $monthId }}">
                                    <span class="me-2">▼</span> {{ $month }}
                                </button>

                                <div class="collapse show" id="collapseMonth-{{ $monthId }}">
                                    @foreach ($orders as $order)
                                        <div class="card border-primary border-2 shadow-sm rounded-4 mb-3"
                                            style="border-color: #4191E8 !important;">
                                            <div class="card-body p-4">
                                                <div class="row h-100 align-items-stretch">

                                                    <div class="col-md-8 d-flex flex-column justify-content-between">
                                                        <div>
                                                            <h5 class="fw-bold mb-3" style="color: #4191E8">#{{ $order->order_id }}</h5>
                                                            <p class="mb-1 fw-bold">Rp.
                                                                {{ number_format($order->total_price, 0, ',', '.') }}
                                                            </p>
                                                            <p class="mb-1 text-muted">{{ $order->user->name }} |
                                                                {{ $order->order_time->format('H:i') }}
                                                            </p>
                                                        </div>

                                                        <div class="d-flex gap-2 mt-3 align-items-center">
                                                            <a href="{{ route('merchant.order.detail', $order->order_id) }}"
                                                                class="btn text-white fw-bold px-4" style="background-color: #4191E8;">
                                                                Lihat Detail
                                                            </a>
                                                            @php
                                                                $statusColor = match ((int) $order->status_id) {
                                                                    4 => 'bg-success',
                                                                    5, 6 => 'bg-danger',
                                                                    default => 'bg-secondary'
                                                                };
                                                            @endphp
                                                            <span
                                                                class="badge {{ $statusColor }} px-3 py-2 fs-6 text-uppercase rounded-3">
                                                                {{ $order->status->name }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 d-flex justify-content-md-end align-items-center mt-3 mt-md-0">
                                                        @if($order->orderItems->isNotEmpty())
                                                            @php $firstItem = $order->orderItems->first(); @endphp
                                                            <div class="card border border-2 border-dark rounded-4 overflow-hidden bg-white shadow-sm"
                                                                style="width: 220px;">
                                                                <div style="height: 120px; overflow: hidden;">
                                                                    <img src="{{ asset($firstItem->menu_item->image) }}" class="w-100 h-100"
                                                                        style="object-fit: cover;">
                                                                </div>
                                                                <div class="card-body p-2 d-flex flex-column justify-content-between"
                                                                    style="min-height: 110px;">
                                                                    <h6 class="fw-bold mb-2 text-start"
                                                                        style="color: #FB8C30; font-size: 0.9rem;">
                                                                        {{ $firstItem->menu_item->name }}
                                                                    </h6>
                                                                    <div class="d-flex justify-content-between align-items-end mt-auto">
                                                                        <div class="px-2 py-1 rounded-3 text-white fw-bold"
                                                                            style="background-color: #4191E8; font-size: 0.75rem;">
                                                                            Rp.
                                                                            {{ number_format($firstItem->menu_item->price, 0, ',', '.') }}
                                                                        </div>
                                                                        <div class="d-flex align-items-baseline">
                                                                            <span class="fw-bold me-1 fs-5 text-dark">&times;</span>
                                                                            <span class="fw-bold"
                                                                                style="color: #4191E8; font-size: 2rem;">{{ $firstItem->quantity }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection