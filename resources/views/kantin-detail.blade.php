@extends('base')

@section('content')
    <div class="container-fluid py-5" style="background-color: #C9E2FE;">
        <div class="row justify-content-center mb-5 px-2 px-sm-3 px-md-4 px-lg-5">
            <div class="col-12 col-md-6 col-lg-7 col-xl-8 mb-4">
                <div id="merchantCard" class="card border-0 shadow rounded-4 p-3 p-md-4 position-relative">
                    <div class="card-img-top rounded-top rounded-4 position-relative d-flex align-items-center justify-content-center"
                        style="height: 400px; object-fit: cover; background-color: {{ $imageExist ? 'transparent' : '#e0e0e0' }}">
                        @if ($imageExist)
                            <img src="{{ asset($merchant->image) }}" alt="Merchant Image"
                                class="rounded-top rounded-4 w-100 h-100" style="object-fit:cover;">
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
                                @if ($isOpen)
                                    {{ __('buka') }}
                                @else
                                    {{ __('tutup') }}
                                @endif
                            </span>
                        </div>

                        <div class="d-flex align-items-center mt-2">
                            <p class="mb-0 me-2" style="color:#4191E8">
                                {{ substr($merchant->open, 0, 5) ?? '00:00' }} -
                                {{ substr($merchant->close, 0, 5) ?? '00:00' }}
                            </p>
                            @if ($isMerchant)
                                <x-pencil color="black" size="20" />
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @auth
                <div class="col-12 col-md-6 col-lg-5 col-xl-4 mb-4">
                    <div id="orderCard" class="card border-0 shadow rounded-4 p-4 d-flex flex-column h-100">

                        @if ($isMerchant)
                            <h3 class="fw-bold mb-2">{{ __('total.pesanan') }}</h3>
                            <p class="fs-4 fw-bold text-primary">{{ $orderCount ?? 0 }}</p>
                            <hr class="my-2">

                            <h3 class="fw-bold mb-2">{{ __('total.transaksi') }}</h3>
                            <p class="fs-4 fw-bold text-primary">Rp.{{ number_format($total, 2, ',', '.') }}</p>
                            <hr class="my-2">

                            <h3 class="fw-bold mb-2">{{ __('total.pembeli') }}</h3>
                            <p class="fs-4 fw-bold text-primary">{{ $customerCount ?? 0 }}</p>
                        @else
                            <h1 class="fw-bold text-center mb-3">{{ __('pesanan') }}</h1>
                            <div id="cartList" class="flex-grow-1 overflow-auto pe-1 mb-3"></div>
                            <button class="btn btn-primary w-100 fw-bold" onclick="openCheckout()">Checkout</button>
                        @endif

                    </div>
                </div>
            @endauth
        </div>
        <div class="row mt-4 mx-2 px-2 mx-sm-3 px-sm-3 mx-md-4 px-md-4 mx-lg-5 px-lg-5">
            <div class="col-12">
                <div
                    class="row g-4
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

                    {{-- Form khusus merchant, muncul setelah daftar menu --}}
                    @if($isMerchant)
                        <div class="row mt-5">
                            <div class="col-12 col-md-6">
                                <h4 class="fw-bold mb-3">Upload Gambar Menu</h4>
                                <form action="{{ route('menu.upload', $merchant->id) }}" method="POST" enctype="multipart/form-data" class="card p-3 shadow-sm rounded-4">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="file" name="image" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 fw-bold">Upload</button>
                                </form>
                            </div>

                            <div class="col-12 col-md-6">
                                <h4 class="fw-bold mb-3">Tambah Menu Baru</h4>
                                <form action="{{ route('menu.add', $merchant->id) }}" method="POST" enctype="multipart/form-data" class="card p-3 shadow-sm rounded-4">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" name="nama_menu" class="form-control" placeholder="Nama Menu" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" name="harga" class="form-control" placeholder="Harga" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="kategori" class="form-control" placeholder="Kategori">
                                    </div>
                                    <div class="mb-3">
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 fw-bold">Tambah Menu</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modals --}}
        @auth
            <div class="modal fade" id="confirmAddModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-body text-center p-4">
                            <h4 class="fw-bold mb-3">{{ __('kantin.tambah.title') }}</h4>
                            <div id="selectedProductPreview" class="mb-3"></div>
                            <p class="fw-bold" id="selectedProductName"></p>
                            <p id="selectedProductPrice" class="text-primary fw-bold"></p>
                            <button class="btn btn-primary w-100 mt-3"
                                id="confirmAddBtn">{{ __('kantin.tambah.true') }}</button>
                            <button class="btn btn-secondary w-100 mt-2"
                                data-bs-dismiss="modal">{{ __('kantin.tambah.f alse') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="confirmCheckoutModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-body p-4">
                            <h3 class="fw-bold mb-3">{{ __('kantin.pesan.konfirmasi') }}</h3>
                            <div id="checkoutList"></div>
                            <hr>
                            <h4 class="fw-bold mt-3">{{ __('kantin.pesan.total') }}: <span id="checkoutTotal"
                                    class="text-success"></span></h4>
                            <button class="btn btn-success w-100 mt-3"
                                id="confirmPaymentBtn">{{ __('kantin.pesan.lanjut') }}</button>
                            <button class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">{{ __('kembali') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- nanti buat qr snap --}}
            {{-- <div class="modal fade" id="qrPaymentModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-body text-center p-4">
                            <h4 class="fw-bold mb-3">{{ __('kantin.pesan.scan') }}</h4>
                            <canvas id="qrcode" width="250" height="250"></canvas>
                            <button class="btn btn-secondary w-100 mt-3" data-bs-dismiss="modal">{{ __('selesai') }}</button>
                        </div>
                    </div>
                </div>
            </div> --}}
        @endauth

    </div>

    <script>
        let cart = [];
        let selectedProduct = null;

        document.addEventListener("DOMContentLoaded", () => {
            const merchantCard = document.getElementById('merchantCard');
            const orderCard = document.getElementById('orderCard');

            @if (!$isMerchant)
                if (merchantCard && orderCard) {
                    orderCard.style.height = merchantCard.offsetHeight + "px";
                }
            @endif
        });

        function selectProduct(name, price, image, id) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.quantity += 1;
            } else {
                selectedProduct = {
                    name,
                    price,
                    image,
                    id,
                    quantity: 1
                };
                cart.push(selectedProduct);
            }
            renderCart();
        }

        document.getElementById("confirmAddBtn").addEventListener("click", () => {
            cart.push(selectedProduct);
            bootstrap.Modal.getInstance(document.getElementById('confirmAddModal')).hide();
            renderCart();
        });

        function renderCart() {
            let html = "";
            cart.forEach(item => {
                html += `
        <div class="card border-0 shadow-sm rounded-4 mb-2 p-2 d-flex flex-row align-items-center">
            <img src="/${item.image}" style="width:60px; height:60px; object-fit:cover; border-radius:10px; margin-right:10px;">
            <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                <div>
                    <strong>${item.name}</strong><br>
                    <span class="text-muted">Rp ${item.price.toLocaleString()}</span>
                </div>
                <span class="px-3 py-2 fw-bold" style="color: #4191E8">${item.quantity}</span>
            </div>
        </div>`;
            });
            document.getElementById("cartList").innerHTML = html;
        }

        function openCheckout() {
            if (cart.length === 0) {
                alert("Belum ada pesanan!");
                return;
            }

            let html = "",
                total = 0;
            cart.forEach(item => {
                total += item.price * item.quantity;
                html += `
        <div class="d-flex mb-3 align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="/${item.image}" style="width:70px; height:70px; object-fit:cover; border-radius:10px; margin-right:12px;">
                <div>
                    <div class="fw-bold">${item.name}</div>
                    <div class="text-muted">Rp ${item.price.toLocaleString()}</div>
                </div>
            </div>
            <div class="fw-bold">x ${item.quantity}</div>
        </div>`;
            });

            document.getElementById("checkoutList").innerHTML = html;
            document.getElementById("checkoutTotal").innerText = "Rp " + total.toLocaleString();
            new bootstrap.Modal(document.getElementById('confirmCheckoutModal')).show();
        }

        document.getElementById("confirmPaymentBtn").addEventListener("click", async () => {
            if (cart.length === 0) return;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch("{{ route('order.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({
                        items: cart
                    })
                });

                if (!response.ok) throw new Error("Gagal membuat pesanan");

                const data = await response.json();
                // alert("Pesanan berhasil dibuat! Invoice: " + data.invoice_number);

                cart = [];
                renderCart();
                bootstrap.Modal.getInstance(document.getElementById('confirmCheckoutModal')).hide();

            } catch (error) {
                console.error(error);
                alert("Gagal membuat pesanan.");
            }
        });
    </script>
@endsection
