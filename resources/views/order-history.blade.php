@extends('base')

@section('content')
<div class="container-fluid min-vh-100 py-5" style="background-color: #C9E2FE;">
    <div class="container">
        <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
            <h2 class="fw-bold m-0">{{ __('orderHistory.title') }}</h2>
        </div>

        @if($groupedOrders->isEmpty())
            <div class="text-center py-5">
                <h4 class="text-muted">{{ __('orderHistory.empty') }}</h4>
                <a href="{{ route('home.page') }}" class="btn btn-primary mt-3">{{ __('orderHistory.order_now') }}</a>
            </div>
        @else
            @foreach ($groupedOrders as $year => $months)
                <div class="mb-2">
                    <button class="btn w-100 text-start fw-bold fs-3 text-primary p-0 mb-2 d-flex align-items-center" 
                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseYear-{{ $year }}" aria-expanded="true">
                        <span class="me-2">▼</span> {{ $year }}
                    </button>

                    <div class="collapse show" id="collapseYear-{{ $year }}">
                        @foreach ($months as $month => $orders)
                            <div class="ms-md-4 mb-4">
                                @php $monthId = $year . '-' . str_replace(' ', '', $month); @endphp
                                <button class="btn w-100 text-start fw-bold fs-4 text-primary p-0 mb-3 d-flex align-items-center" 
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseMonth-{{ $monthId }}" aria-expanded="true">
                                    <span class="me-2">▼</span> {{ $month }}
                                </button>

                                <div class="collapse show" id="collapseMonth-{{ $monthId }}">
                                    @foreach ($orders as $order)
                                        <div class="card border-primary border-2 shadow-sm rounded-4 mb-3" style="border-color: #4191E8 !important;">
                                            <div class="card-body p-4">
                                                <div class="row h-100 align-items-stretch">
                                                    
                                                    <div class="col-md-8 d-flex flex-column justify-content-between">
                                                        <div>
                                                            <h5 class="fw-bold mb-3" style="color: #4191E8">#{{ $order->order_id }}</h5>
                                                            <p class="mb-1 fw-bold">{{ __('orderHistory.total_price') }} : Rp. {{ number_format($order->total_price, 2, ',', '.') }}</p>
                                                            <p class="mb-1 text-muted">{{ __('orderHistory.merchant') }} : {{ $order->merchant->user->name ?? 'Merchant' }}</p>
                                                            <p class="mb-1 text-muted">{{ __('orderHistory.time') }} : {{ $order->order_time->format('d F H:i') }}</p>
                                                        </div>

                                                        <div class="d-flex gap-2 mt-3">
                                                            <a href="{{ route('order.detail', $order->order_id) }}" 
                                                               class="btn text-white fw-bold px-4" style="background-color: #4191E8;">
                                                                {{ __('orderHistory.view_button') }}
                                                            </a>
                                                            
                                                            @php
                                                                $statusColor = match(strtoupper($order->status->name)) {
                                                                    'SELESAI' => 'bg-success',
                                                                    'DITERIMA' => 'bg-secondary',
                                                                    'DISIAPKAN' => 'bg-secondary',
                                                                    'KONFIRMASI' => 'bg-warning text-dark',
                                                                    'DITOLAK', 'DIBATALKAN' => 'bg-danger',
                                                                    default => 'bg-secondary'
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $statusColor }} d-flex align-items-center px-3 py-2 fs-6 text-uppercase">
                                                                {{ $order->status->name }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 d-flex justify-content-md-end align-items-center mt-3 mt-md-0">
                                                        @if($order->orderItems->isNotEmpty())
                                                            @php $firstItem = $order->orderItems->first(); @endphp
                                                            <div class="card border border-2 border-dark rounded-4 overflow-hidden bg-white shadow-sm" style="width: 220px;">
                                                                <div style="height: 120px; overflow: hidden;">
                                                                    <img src="{{ asset($firstItem->menu_item->image) }}" class="w-100 h-100" style="object-fit: cover;">
                                                                </div>
                                                                <div class="card-body p-2 d-flex flex-column justify-content-between" style="min-height: 110px;">
                                                                    <h6 class="fw-bold mb-2 text-start" style="color: #FB8C30; font-size: 0.9rem; line-height: 1.2; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                                        {{ $firstItem->menu_item->name }}
                                                                    </h6>
                                                                    <div class="d-flex justify-content-between align-items-end mt-auto">
                                                                        <div class="px-2 py-1 rounded-3 text-white fw-bold" style="background-color: #4191E8; font-size: 0.75rem;">
                                                                            Rp. {{ number_format($firstItem->menu_item->price, 0, ',', '.') }}
                                                                        </div>
                                                                        <div class="d-flex align-items-baseline">
                                                                            <span class="fw-bold me-1 fs-5 text-dark">&times;</span>
                                                                            <span class="fw-bold" style="color: #4191E8; font-size: 2rem; line-height: 0.8;">{{ $firstItem->quantity }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="d-flex align-items-center justify-content-center border rounded bg-light text-muted small" style="width: 220px; height: 230px;">Kosong</div>
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
        @endif
    </div>
</div>
@endsection