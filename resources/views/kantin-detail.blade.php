@extends('base')

@section('content')
{{-- KRITIS: Meta tag CSRF ini harus ada untuk Fetch API bekerja --}}
<meta name="csrf-token" content="{{ csrf_token() }}"> 

<div style="background-color: #C9E2FE" class="container-fluid py-5">

    {{-- ROW ATAS (Merchant Card & Order Card) --}}
    <div class="row justify-content-center mb-5">

        {{-- CARD MERCHANT (Detail Penjual) --}}
        <div class="col-12 col-md-8 col-lg-6 mb-4">
            <div id="merchantCard" class="card border-0 shadow rounded-4 p-4 position-relative">
                
                {{-- ... Konten Merchant Card ... --}}
                <div class="card-img-top rounded-top rounded-4 position-relative d-flex align-items-center justify-content-center"
                    style="height: 400px; object-fit: cover; background-color: {{ $imageExist ? 'transparent' : '#e0e0e0' }}">
                    {{-- ... Image check ... --}}
                </div>
                
                <div class="card-body">
                    {{-- ... Merchant Details ... --}}
                </div>
            </div>
        </div>

        {{-- ORDER CARD (Keranjang) --}}
        <div class="col-12 col-md-4 col-lg-3 mb-4">
            <div id="orderCard" class="card border-0 shadow rounded-4 p-4 d-flex flex-column"
                style="{{ !$isMerchant ? 'height: 450px;' : '' }}">

                @if ($isMerchant)
                    {{-- Konten Merchant --}}
                    <h1 class="fw-bold text-center mb-1">Kelola Pesanan</h1>
                    {{-- ... Tampilan merchant ... --}}
                @else
                    {{-- Tampilan Keranjang untuk Customer --}}
                    <h1 class="fw-bold text-center mb-1">>Your Orders</h1>
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
                        {{-- KRITIS: Inline onclick memanggil fungsi JS global --}}
                        <div class="card p-3 shadow-sm" 
                             onclick="selectProduct('{{ $menu->name }}', {{ $menu->price }}, '{{ $menu->image }}')">
                            
                            <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}"
                                style="height: 150px; object-fit: cover;" class="card-img-top">
                            <h5 class="fw-bold">{{ $menu->name }}</h5>
                            <p>Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                            
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- MODALS (Confirm Add, Confirm Checkout, QR Payment) ... --}}
{{-- ... Pastikan semua modal ada di sini ... --}}
<div class="modal fade" id="confirmAddModal" tabindex="-1">
    {{-- ... content modal ... --}}
</div>
<div class="modal fade" id="confirmCheckoutModal" tabindex="-1">
    {{-- ... content modal ... --}}
</div>
<div class="modal fade" id="qrPaymentModal" tabindex="-1">
    {{-- ... content modal ... --}}
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrPaymentModalLabel">Pembayaran QRIS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h4>Scan QRIS untuk Pembayaran</h4>
                <div id="qrPlaceholder" style="width: 250px; height: 250px; margin: 20px auto;"></div>
                <p class="mt-3">Mohon selesaikan pembayaran sebelum kedaluwarsa.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Selesai</button>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- KRITIS: SCRIPT HARUS DI DALAM @section('scripts') --}}
@section('scripts')
<script>
let cart = [];
let selectedProduct = null;

// 🟦 PILIH PRODUK (GLOBAL: dipanggil dari card menu)
// Didefinisikan di window agar bisa dipanggil dari inline onclick="..."
window.selectProduct = function(name, price, image) {
    selectedProduct = { name, price: parseInt(price), image }; 
    
    document.getElementById("selectedProductName").innerText = name;
    document.getElementById("selectedProductPrice").innerText = "Rp " + selectedProduct.price.toLocaleString('id-ID');

    document.getElementById("selectedProductPreview").innerHTML =
        `<img src="/${image}" class="img-fluid rounded mb-2" style="max-height:150px; object-fit:cover;">`;

    new bootstrap.Modal(document.getElementById('confirmAddModal')).show();
}

// 🟦 HAPUS ITEM DARI CART (GLOBAL)
window.removeItem = function(index) {
    cart.splice(index, 1); 
    renderCart();
};

// 🟦 BUKA CHECKOUT (GLOBAL)
window.openCheckout = function() {
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
                <div class="text-muted">Rp ${item.price.toLocaleString('id-ID')}</div>
            </div>
        </div>`;
    });

    document.getElementById("checkoutList").innerHTML = html;
    document.getElementById("checkoutTotal").innerText = "Rp " + total.toLocaleString('id-ID');

    // Menggunakan jQuery untuk memanggil modal Bootstrap
    $('#confirmCheckoutModal').modal('show');
}

// 🟦 TAMPILKAN CART (Render)
function renderCart() {
    let html = "";
    cart.forEach((item, index) => { 
        html += `
        <div class="card border-0 shadow-sm rounded-4 mb-2 p-2 d-flex flex-row align-items-center">
            <img src="/${item.image}"
                style="width:60px; height:60px; object-fit:cover; border-radius:10px; margin-right:10px;">
            <div class="w-100 d-flex justify-content-between">
                <strong>${item.name}</strong>
                <span>Rp ${item.price.toLocaleString('id-ID')}</span>
            </div>
            <button class="btn btn-sm btn-danger ms-2" onclick="removeItem(${index})" title="Hapus Item">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1zM3 2v1h10V2h-10a1 1 0 0 0-1 1m6-1a1 1 0 0 0-1 1v1h2V2a1 1 0 0 0-1-1z"/>
                </svg>
            </button>
        </div>`;
    });
    document.getElementById("cartList").innerHTML = html;
}


// KRITIS: Menggunakan $(document).ready() untuk memastikan jQuery dimuat.
$(document).ready(function() {
    
    // 🟦 KONFIRMASI TAMBAH PRODUK (Handler Tombol)
    document.getElementById("confirmAddBtn").addEventListener("click", () => {
        cart.push(selectedProduct);
        $('#confirmAddModal').modal('hide');
        renderCart();
    });

    // 🟦 GENERATE QR (Midtrans API Call via Laravel Controller)
    document.getElementById("confirmPaymentBtn").addEventListener("click", () => {
        
        let total = cart.reduce((sum, item) => sum + item.price, 0);

        // 1. Kelola Modal
        $('#confirmCheckoutModal').modal('hide');
        $('#qrPaymentModal').modal('show');
        
        // Tampilkan Loader
        const qrPlaceholder = document.getElementById('qrPlaceholder');
        qrPlaceholder.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p>Memuat QRIS...</p></div>'; 
        
        // 2. Panggil API Laravel
        fetch('{{ route("payment.create") }}', { // Menggunakan route helper untuk keamanan
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            },
            body: JSON.stringify({ total: total.toString() }) 
        }) 
        .then(response => {
            // ... (sisa logic error handling dan JSON parsing) ...
            if (!response.ok) {
                return response.json().then(err => { 
                    throw new Error(err.message || `HTTP Error: ${response.status}`); 
                }).catch(() => {
                    throw new Error(`Internal Server Error. Controller PHP gagal memproses (Status ${response.status}).`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // 3. Sukses: Tampilkan QR Code (Gambar Midtrans)
                qrPlaceholder.innerHTML = ''; 
                const img = document.createElement('img');
                img.src = data.qr_url; 
                img.alt = 'QRIS Payment Code';
                img.style.width = '250px'; 
                img.style.height = '250px';
                qrPlaceholder.appendChild(img);
                
                // Opsional: Kosongkan keranjang setelah QR berhasil dibuat
                // cart = []; // Biarkan sementara untuk testing
                // renderCart(); // Biarkan sementara untuk testing
                
            } else {
                // 4. Handle Error Logis dari Controller
                qrPlaceholder.innerHTML = `<p class="text-danger">Gagal membuat QRIS. Detail: ${data.message}</p>`;
            }
        })
        .catch(error => {
            // 5. Handle Error Koneksi/Server
            qrPlaceholder.innerHTML = `<p class="text-danger">Terjadi kesalahan koneksi atau server tidak merespons. Detail: ${error.message}</p>`;
        });
    });
});
</script>
@endsection