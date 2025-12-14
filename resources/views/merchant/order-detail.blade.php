@extends('base')

@section('content')
    <div class="container-fluid min-vh-100 py-5" style="background-color: #C9E2FE;">
        <div class="container">

            <a href="{{ route('merchant.order.history') }}" class="btn btn-light fw-bold mb-4 shadow-sm" style="color: #4191E8">
                <x-arrow/>
            </a>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white p-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold m-0 text-dark">{{ __('orderHistory.detail_title') }}</h4>
                                    <p class="text-muted m-0 small">{{ __('orderHistory.order_id') }}:
                                        #{{ $order->invoice_number }}</p>
                                </div>
                                @if ($order->status_id == 1)
                                    <button class="btn fw-bold px-3 text-white"
                                        style='cursor: default; background-color: #4191E8'>{{ __('diterima') }}</button>
                                @elseif ($order->status_id == 2)
                                    <button class="btn fw-bold px-3 text-white"
                                        style='cursor: default; background-color: #FB8C30'>{{ __('disiapkan') }}</button>
                                @elseif ($order->status_id == 3)
                                    <button class="btn fw-bold px-3 text-white"
                                        style='cursor-default; background-color: #B85BFF'>{{ __('selesai.caps') }}</button>
                                @elseif ($order->status_id == 4)
                                    <button class="btn fw-bold px-3 text-white"
                                        style='cursor: default; background-color: #53DB09'>{{ __('selesai.pesanan') }}</button>
                                @elseif ($order->status_id == 4)
                                    <button class="btn fw-bold px-3 text-white"
                                        style='cursor: default; background-color: #F20A0A'>{{ __('ditolak') }}</button>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-4 bg-white">
                            <div class="mb-4 pb-3 border-bottom">
                                <h6 class="fw-bold text-muted text-uppercase small">{{ __('pembeli') }}</h6>
                                <h5 class="fw-bold"  style="color: #4191E8">{{ $order->user->name }}</h5>
                                <p class="text-muted m-0 small">{{ $order->order_time->format('l, d F Y - H:i') }} WIB</p>
                            </div>

                            <h6 class="fw-bold text-muted text-uppercase small mb-3">{{ __('orderHistory.items') }}</h6>

                            @foreach ($order->orderItems as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $item->menu_item->image_url }}"
                                        class="rounded-3 shadow-sm border border-dark"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="fw-bold m-0 text-dark">{{ $item->menu_item->name }}</h6>
                                        <p class="text-muted small m-0">
                                            Rp {{ number_format($item->menu_item->price, 2, ',', '.') }} x
                                            {{ $item->quantity }}
                                        </p>
                                    </div>
                                    <div class="fw-bold text-dark">
                                        Rp {{ number_format($item->quantity * $item->menu_item->price, 2, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold text-dark">{{ __('orderHistory.total_payment') }}</h5>
                                <h4 class="fw-bold" style="color: #4191E8">Rp {{ number_format($order->total_price, 2, ',', '.') }}
                                </h4>
                            </div>
                        </div>

                        <div class="card-footer bg-light p-4 text-center">
                            <p class="small text-muted m-0">{{ __('orderHistory.thanks') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
