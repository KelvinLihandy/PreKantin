@extends('base')

@section('content')
<div style="background-color: #C9E2FE" class="container-fluid py-5">

    {{-- ROW ATAS --}}
    <div class="row justify-content-center mb-5">

        {{-- CARD MERCHANT --}}
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
                            style="width:50px; height:50px; cursor:pointer; background-color:#4191E8">
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
                            {{ $isOpen ? __('buka') : __('tutup') }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center mt-2">
                        <p class="mb-0 me-2" style="color:#4191E8">
                            {{ $merchant->open ?? '00:00' }} - {{ $merchant->close ?? '00:00' }}
                        </p>

                        @if ($isMerchant)
                            <x-pencil color="black" size="20" />
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- ORDER CARD --}}
        <div class="col-12 col-md-4 col-lg-3 mb-4">
            <div id="orderCard" class="card border-0 shadow rounded-4 p-4 d-flex flex-column"
                 style="{{ !$isMerchant ? 'height: 450px;' : '' }}">

                @if ($isMerchant)

                    <h3 class="fw-bold">{{ __('total.pesanan') }}</h3>
                    <p class="fs-4 fw-bold" style="color:#4191E8">{{ $orderCount ?? 0 }}</p>
                    <hr>

                    <h3 class="fw-bold">>{{ __('total.transaksi') }}</h3>
                    <p class="fs-4 fw-bold" style="color:#4191E8">Rp.{{ number_format($total, 2, ',', '.') }}</p>
                    <hr>

                    <h3 class="fw-bold">>{{ __('total.pembeli') }}</h3>
                    <p class="fs-4 fw-bold" style="color:#4191E8">{{ $customerCount ?? 0 }}</p>

                @else

                    <h1 class="fw-bold text-center mb-1">>{{ __('pesanan') }}</h1>
                    <hr>

                    {{-- tempat pesanan tampil --}}
                    <div id="cartList" class="flex-grow-1 overflow-auto pe-1"></div>

                    <button class="btn btn-primary w-100 mt-3 fw-bold" onclick="openCheckout()">
                        Checkout
                    </button>

                @endif

            </div>
        </div>

    </div> {{-- END ROW ATAS --}}

    {{-- LIST MENU --}}
    <div class="row mt-4 mx-2 px-2 mx-sm-3 px-sm-3 mx-md-4 px-md-4 mx-lg-5 px-lg-5">
        <div class="col-12">
            <div class="row g-4
                row-cols-1 row-cols-sm-2
                row-cols-md-3 row-cols-lg-4 row-cols-xl-6">

                @foreach ($menus as $menu)
                    <div class="col">
                        <x-menu-card-kantin-detail 
                            name="{{ $menu->name }}"
                            price="{{ $menu->price }}"
                            image="{{ $menu->image }}"
                            merchant="{{ $isMerchant }}" />
                    </div>
                @endforeach

            </div>
        </div>
    </div>

</div>
{{-- MODAL TAMBAH PRODUK --}}
<div class="modal fade" id="confirmAddModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-body text-center p-4">

                <h4 class="fw-bold mb-3">Tambahkan ke Pesanan?</h4>

                <div id="selectedProductPreview" class="mb-3"></div>

                <p class="fw-bold" id="selectedProductName"></p>
                <p id="selectedProductPrice" class="text-primary fw-bold"></p>

                <button class="btn btn-primary w-100 mt-3" id="confirmAddBtn">Tambahkan</button>
                <button class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">
                    Batal
                </button>

            </div>
        </div>
    </div>
</div>



{{-- MODAL KONFIRMASI CHECKOUT --}}
<div class="modal fade" id="confirmCheckoutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-body p-4">

                <h3 class="fw-bold mb-3">Konfirmasi Pesanan</h3>

                <div id="checkoutList"></div>

                <hr>
                <h4 class="fw-bold mt-3">
                    Total: <span id="checkoutTotal" class="text-success"></span>
                </h4>

                <button class="btn btn-success w-100 mt-3" id="confirmPaymentBtn">
                    Lanjut ke Pembayaran
                </button>

                <button class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">
                    Kembali
                </button>

            </div>
        </div>
    </div>
</div>


{{-- MODAL QR PEMBAYARAN --}}
<div class="modal fade" id="qrPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-body text-center p-4">
                <h4 class="fw-bold mb-3">Scan QR untuk Membayar</h4>

                <canvas id="qrcode" width="250" height="250"></canvas>

                <button class="btn btn-secondary w-100 mt-3" data-bs-dismiss="modal">
                    Selesai
                </button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>



<script>

let cart = [];
let selectedProduct = null;


// KLIK PRODUK ⇒ TAMPILKAN MODAL KONFIRMASI
function selectProduct(name, price, image) {
    selectedProduct = { name, price, image };

    document.getElementById("selectedProductName").innerText = name;
    document.getElementById("selectedProductPrice").innerText =
        "Rp " + price.toLocaleString();

    document.getElementById("selectedProductPreview").innerHTML =
        `<img src="/${image}" class="img-fluid rounded mb-2"
              style="max-height:150px; object-fit:cover;">`;

    new bootstrap.Modal(document.getElementById('confirmAddModal')).show();
}


// KONFIRMASI TAMBAH PRODUK KE CART
document.getElementById("confirmAddBtn").addEventListener("click", () => {
    cart.push(selectedProduct);

    bootstrap.Modal.getInstance(
        document.getElementById('confirmAddModal')
    ).hide();

    renderCart();
});


// RENDER CART DI YOUR ORDER
function renderCart() {
    let html = "";

    cart.forEach(item => {
        html += `
            <div class="card border-0 shadow-sm rounded-4 mb-2 p-2 d-flex flex-row align-items-center">
                <img src="/${item.image}"
                     style="width:60px; height:60px; object-fit:cover; border-radius:10px; margin-right:10px;">
                <div class="w-100 d-flex justify-content-between">
                    <strong>${item.name}</strong>
                    <span>Rp ${item.price.toLocaleString()}</span>
                </div>
            </div>`;
    });

    document.getElementById("cartList").innerHTML = html;
}


// BUKA CHECKOUT
function openCheckout() {
    if (cart.length === 0) {
        alert("Belum ada pesanan!");
        return;
    }

    let html = "";
    let total = 0;

    cart.forEach(item => {
        total += item.price;
        html += `
            <div class="d-flex mb-3 align-items-center">
                <img src="/${item.image}"
                     style="width:70px; height:70px; object-fit:cover; border-radius:10px; margin-right:12px;">
                <div class="flex-grow-1">
                    <div class="fw-bold">${item.name}</div>
                    <div class="text-muted">Rp ${item.price.toLocaleString()}</div>
                </div>
            </div>`;
    });

    document.getElementById("checkoutList").innerHTML = html;
    document.getElementById("checkoutTotal").innerText =
        "Rp " + total.toLocaleString();

    new bootstrap.Modal(document.getElementById('confirmCheckoutModal')).show();
}


// GENERATE QR PEMBAYARAN (BELUM PAKAI MIDTRANS)
document.getElementById("confirmPaymentBtn").addEventListener("click", async () => {

    let total = cart.reduce((sum, item) => sum + item.price, 0);

    bootstrap.Modal.getInstance(
        document.getElementById('confirmCheckoutModal')
    ).hide();

    const qrModal = new bootstrap.Modal(document.getElementById('qrPaymentModal'));
    qrModal.show();

    document.getElementById("qrcode").getContext('2d').clearRect(0,0,250,250);

    setTimeout(() => {

        QRCode.toCanvas(
            document.getElementById("qrcode"),
            `TOTAL=${total}|ITEMS=${cart.length}`,
            { width: 250 },
            (err) => {
                if (err) console.error(err);
            }
        );

    }, 500);
});
</script>
@endsection