@extends('base')

@section('content')
    <div class="container-fluid min-vh-100 py-5" style="background-color: #C9E2FE;">
        <div class="container">

            <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
                <h2 class="fw-bold m-0">{{ __('merchant.history.title') }}</h2>
            </div>

            <h3 class="fw-bold mb-3 mt-5 px-2" style="color: #4191E8">{{ __('merchant.history.order') }}</h3>

            @if ($activeOrders->isEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="mb-4">
                        <svg width="120" height="120" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" style="opacity: 0.3;">
                            <path
                                d="M9 2C9 1.44772 9.44772 1 10 1H14C14.5523 1 15 1.44772 15 2V3H19C19.5523 3 20 3.44772 20 4V6C20 6.55228 19.5523 7 19 7H5C4.44772 7 4 6.55228 4 6V4C4 3.44772 4.44772 3 5 3H9V2Z"
                                fill="#4191E8" />
                            <path d="M5 8H19V20C19 21.1046 18.1046 22 17 22H7C5.89543 22 5 21.1046 5 20V8Z"
                                fill="#4191E8" />
                            <path d="M9 11V18" stroke="white" stroke-width="2" stroke-linecap="round" />
                            <path d="M15 11V18" stroke="white" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <p class="m-0 text-muted">{{ __('merchant.history.empty') }}</p>
                </div>
            @else
                @foreach ($activeOrders as $order)
                    <div class="card border-2 shadow-sm rounded-4 mb-4" style="border-color: #4191E8 !important;">
                        <div class="card-body p-4">
                            <div class="row h-100 align-items-stretch">
                                <div class="col-md-7 d-flex flex-column justify-content-between">
                                    <div>
                                        <h5 class="fw-bold mb-3" style="color: #4191E8">
                                            #{{ $order->invoice_number }}</h5>
                                        <p class="mb-1 fw-bold">
                                            {{ __('orderHistory.total_price') }} : Rp.
                                            {{ number_format($order->total_price, 2, ',', '.') }}
                                        </p>
                                        <p class="mb-1 text-muted">
                                            {{ __('orderHistory.merchant') }} :
                                            {{ $order->merchant->user->name ?? 'Merchant' }}</p>
                                        <p class="mb-1 text-muted">{{ __('orderHistory.time') }} :
                                            {{ $order->order_time->format('d F H:i') }}</p>
                                    </div>

                                    <div class="d-flex gap-2 mt-3">
                                        <a href="{{ route('merchant.order.detail', $order->invoice_number) }}"
                                            class="btn text-white fw-bold px-4" style="background-color: #4191E8;">
                                            {{ __('orderHistory.view_button') }}
                                        </a>
                                        @if ($order->status_id == 1)
                                            <form action="{{ route('order.update', $order->order_id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_id" value="2">
                                                <button class="btn fw-bold px-3 text-white"
                                                    style='background-color: #4191E8'>{{ __('siapkan') }}</button>
                                            </form>
                                            <form action="{{ route('order.update', $order->order_id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_id" value="5">
                                                <button class="btn fw-bold px-3 text-white"
                                                    style="background-color: #F20A0A">{{ __('tolak') }}</button>
                                            </form>
                                        @endif
                                        @if ($order->status_id == 2)
                                            <form action="{{ route('order.update', $order->order_id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_id" value="3">
                                                <button class="btn fw-bold px-3 text-white"
                                                    style="background-color: #FB8C30">{{ __('selesai.caps') }}</button>
                                            </form>
                                        @endif
                                        @if ($order->status_id == 3)
                                            <button class="btn fw-bold px-3 text-white"
                                                style="cursor: default; background-color: #B85BFF">{{ __('belum.ambil') }}</button>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-5 mt-3 mt-md-0">
                                    <div class="d-flex gap-3 {{ count($order->orderItems) <= 2 ? 'justify-content-end' : '' }}"
                                        style="
                                            max-width:100%;
                                            overflow-x:auto;
                                            padding-bottom:6px;
                                        "
                                        onmouseenter="this.style.overflowX='auto'"
                                        onmouseleave="this.style.overflowX='hidden'">
                                        @foreach ($order->orderItems as $item)
                                            <div class="card border border-2 rounded-4 shadow-sm flex-shrink-0"
                                                style="width:200px;">
                                                <div style="height:120px;overflow:hidden;">
                                                    <img src="{{ $item->menu_item->image_url }}"
                                                        style="width:100%;height:100%;object-fit:cover;">
                                                </div>
                                                <div class="card-body p-2">
                                                    <div class="fw-bold mb-1"
                                                        style="
                                                            font-size:0.85rem;
                                                            color:#FB8C30;
                                                            display:-webkit-box;
                                                            -webkit-line-clamp:2;
                                                            -webkit-box-orient:vertical;
                                                            overflow:hidden;
                                                            ">
                                                        {{ $item->menu_item->name }}
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="px-2 py-1 rounded-3 text-white fw-bold"
                                                            style="background-color:#4191E8;font-size:0.75rem;">
                                                            Rp.
                                                            {{ number_format($item->menu_item->price, 2, ',', '.') }}
                                                        </div>
                                                        <div class="fw-bold" style="color:#4191E8; font-size:1.3rem;">
                                                            ×{{ $item->quantity }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <hr class="my-5 border-3 opacity-25" style="color: #4191E8">

            <h3 class="fw-bold mb-3 px-2" style="color: #4191E8">{{ __('riwayat.transaksi') }}</h3>

            @foreach ($groupedHistory as $year => $months)
                <div class="mb-2">
                    <button class="btn w-100 text-start fw-bold fs-3 p-0 mb-2 d-flex align-items-center"
                        style="color:#4191E8" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseYear-{{ $year }}" aria-expanded="true"
                        onclick="
                        setTimeout(() => {
                            const i=this.querySelector('.toggle-icon');
                            i.textContent=this.getAttribute('aria-expanded')==='true'?'▼':'▶';
                        },0);
                    ">
                        <span class="toggle-icon me-2">▼</span>{{ $year }}
                    </button>

                    <div class="collapse show" id="collapseYear-{{ $year }}">
                        @foreach ($months as $month => $orders)
                            @php $monthId=$year.'-'.str_replace(' ','',$month); @endphp
                            <div class="ms-md-4 mb-4">
                                <button class="btn w-100 text-start fw-bold fs-4 p-0 mb-3 d-flex align-items-center"
                                    style="color:#4191E8" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseMonth-{{ $monthId }}" aria-expanded="true"
                                    onclick="
                                    setTimeout(() => {
                                        const i=this.querySelector('.toggle-icon');
                                        i.textContent=this.getAttribute('aria-expanded')==='true'?'▼':'▶';
                                    },0);
                                ">
                                    <span class="toggle-icon me-2">▼</span>{{ $month }}
                                </button>

                                <div class="collapse show" id="collapseMonth-{{ $monthId }}">
                                    @foreach ($orders as $order)
                                        <div class="card border-2 shadow-sm rounded-4 mb-3"
                                            style="border-color: #4191E8 !important;">
                                            <div class="card-body p-4">
                                                <div class="row h-100 align-items-stretch">
                                                    <div class="col-md-7 d-flex flex-column justify-content-between">
                                                        <div>
                                                            <h5 class="fw-bold mb-3" style="color: #4191E8">
                                                                #{{ $order->invoice_number }}</h5>
                                                            <p class="mb-1 fw-bold">
                                                                {{ __('orderHistory.total_price') }} : Rp.
                                                                {{ number_format($order->total_price, 2, ',', '.') }}
                                                            </p>
                                                            <p class="mb-1 text-muted">
                                                                {{ __('orderHistory.merchant') }} :
                                                                {{ $order->merchant->user->name ?? 'Merchant' }}</p>
                                                            <p class="mb-1 text-muted">{{ __('orderHistory.time') }} :
                                                                {{ $order->order_time->format('d F H:i') }}</p>
                                                        </div>

                                                        <div class="d-flex gap-2 mt-3">
                                                            <a href="{{ route('merchant.order.detail', $order->invoice_number) }}"
                                                                class="btn text-white fw-bold px-4"
                                                                style="background-color: #4191E8;">
                                                                {{ __('orderHistory.view_button') }}
                                                            </a>
                                                            @if ($order->status_id == 4)
                                                                <button class="btn fw-bold px-3 text-white"
                                                                    style='cursor: default; background-color: #53DB09'>{{ __('selesai.pesanan') }}</button>
                                                            @elseif ($order->status_id == 5)
                                                                <button class="btn fw-bold px-3 text-white"
                                                                    style='cursor: default; background-color: #F20A0A'>{{ __('ditolak') }}</button>
                                                            @else
                                                                <button class="btn fw-bold px-3 text-white"
                                                                    style='cursor: default; background-color: #6c757d'>ERROR</button>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5 mt-3 mt-md-0">
                                                        <div class="d-flex gap-3 {{ count($order->orderItems) <= 2 ? 'justify-content-end' : '' }}"
                                                            style="
                                                                max-width:100%;
                                                                overflow-x:auto;
                                                                padding-bottom:6px;
                                                            "
                                                            onmouseenter="this.style.overflowX='auto'"
                                                            onmouseleave="this.style.overflowX='hidden'">
                                                            @foreach ($order->orderItems as $item)
                                                                <div class="card border border-2 rounded-4 shadow-sm flex-shrink-0"
                                                                    style="width:200px;">
                                                                    <div style="height:120px;overflow:hidden;">
                                                                        <img src="{{ $item->menu_item->image_url }}"
                                                                            style="width:100%;height:100%;object-fit:cover;">
                                                                    </div>
                                                                    <div class="card-body p-2">
                                                                        <div class="fw-bold mb-1"
                                                                            style="
                                                                                font-size:0.85rem;
                                                                                color:#FB8C30;
                                                                                display:-webkit-box;
                                                                                -webkit-line-clamp:2;
                                                                                -webkit-box-orient:vertical;
                                                                                overflow:hidden;
                                                                            ">
                                                                            {{ $item->menu_item->name }}
                                                                        </div>
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center">
                                                                            <div class="px-2 py-1 rounded-3 text-white fw-bold"
                                                                                style="background-color:#4191E8;font-size:0.75rem;">
                                                                                Rp.
                                                                                {{ number_format($item->menu_item->price, 2, ',', '.') }}
                                                                            </div>
                                                                            <div class="fw-bold"
                                                                                style="color:#4191E8;font-size:1.3rem;">
                                                                                ×{{ $item->quantity }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
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
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            @if (session('success'))
                <div id="uploadToast" class="toast align-items-center text-bg-success border-0" role="alert"
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
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            var uploadToastEl = document.getElementById('uploadToast');
            if (uploadToastEl) {
                var uploadToast = new bootstrap.Toast(uploadToastEl, {
                    delay: 10000
                });
                uploadToast.show();
            }
        });
    </script>
@endsection
