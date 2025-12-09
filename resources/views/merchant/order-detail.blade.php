@extends('base')

@section('content')
<div class="container-fluid min-vh-100 py-5" style="background-color: #C9E2FE;">
    <div class="container">
        
        <a href="{{ route('merchant.order.history') }}" class="btn btn-light fw-bold mb-4 shadow-sm text-primary">
            <span class="me-2">←</span> Back
        </a>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold m-0 text-dark">Order Details</h4>
                                <p class="text-muted m-0 small">Order ID: #{{ $order->order_id }}</p>
                            </div>
                            <span class="badge bg-primary fs-6 px-3 py-2 rounded-3">{{ $order->status->name }}</span>
                        </div>
                    </div>

                    <div class="card-body p-4 bg-white">
                        <div class="mb-4 pb-3 border-bottom">
                            <h6 class="fw-bold text-muted text-uppercase small">Buyer</h6>
                            <h5 class="fw-bold text-primary">{{ $order->user->name }}</h5>
                            <p class="text-muted m-0 small">{{ $order->order_time->format('l, d F Y - H:i') }} WIB</p>
                        </div>

                        <h6 class="fw-bold text-muted text-uppercase small mb-3">Item Makanan</h6>
                        
                        @foreach($order->orderItems as $item)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset($item->menu_item->image) }}" class="rounded-3 shadow-sm border border-dark" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="ms-3 flex-grow-1">
                                <h6 class="fw-bold m-0 text-dark">{{ $item->menu_item->name }}</h6>
                                <p class="text-muted small m-0">
                                    Rp {{ number_format($item->menu_item->price, 0, ',', '.') }} x {{ $item->quantity }}
                                </p>
                            </div>
                            <div class="fw-bold text-dark">
                                Rp {{ number_format($item->quantity * $item->menu_item->price, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-dark">Total Payment</h5>
                            <h4 class="fw-bold text-primary">Rp {{ number_format($order->total_price, 2, ',', '.') }}</h4>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-4 text-center">
                        <p class="small text-muted m-0">Thank you for serving your foods!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection