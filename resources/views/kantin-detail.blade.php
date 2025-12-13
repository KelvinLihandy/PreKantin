@extends('base')

@section('content')
    <div class="container-fluid py-5" style="background-color: #C9E2FE;">
        <div class="row justify-content-center mb-5 px-2 px-sm-3 px-md-4 px-lg-5">
            @if (Auth::user())
                <div class="col-12 col-md-6 col-lg-7 col-xl-8 mb-4">
                @else
                    <div class="col-12 col-md-10 col-lg-8 col-xl-8 mb-4">
            @endif
            <div id="merchantCard" class="card border-0 shadow rounded-4 p-3 p-md-4 position-relative">
                <div class="card-img-top rounded-top rounded-4 position-relative d-flex align-items-center justify-content-center {{ auth()->check() ? 'img-auth' : 'img-guest' }}"
                    style="object-fit: cover; background-color: {{ $imageExist ? 'transparent' : '#e0e0e0' }}">
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

        @if ($isOpen)
            @auth
                <div class="col-12 col-md-6 col-lg-5 col-xl-4 mb-4">
                    <div id="orderCard" class="card border-0 shadow rounded-4 p-4 d-flex flex-column h-100">
                        @if ($isMerchant)
                            <h3 class="fw-bold mb-2">{{ __('total.pesanan') }}</h3>
                            <p class="fs-4 fw-bold" style="color: #4191E8">{{ $orderCount ?? 0 }}</p>
                            <hr class="my-2">

                            <h3 class="fw-bold mb-2">{{ __('total.transaksi') }}</h3>
                            <p class="fs-4 fw-bold" style="color: #4191E8">Rp.{{ number_format($total, 2, ',', '.') }}</p>
                            <hr class="my-2">

                            <h3 class="fw-bold mb-2">{{ __('total.pembeli') }}</h3>
                            <p class="fs-4 fw-bold" style="color: #4191E8">{{ $customerCount ?? 0 }}</p>
                        @else
                            <h1 class="fw-bold text-center mb-3">{{ __('pesanan') }}</h1>
                            <div id="cartList" class="flex-grow-1 overflow-auto pe-1 mb-3"></div>
                            <button id="checkoutBtn" class="btn w-100 fw-bold text-white" onclick="openCheckout()"
                                style="background-color: #4191E8" disabled>Checkout</button>
                        @endif

                    </div>
                </div>
            @endauth
        @endif
    </div>

    <div class="row g-4 px-2 px-sm-3 px-md-4 px-lg-5 mb-5">
        @if ($isMerchant)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <x-menu-card-kantin-detail add />
            </div>
        @endif
        @if ($menus->isEmpty())
            @unless ($isMerchant)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center d-flex align-items-center justify-content-center"
                        style="background-color: #f8f9fa;">
                        <h4 class="fw-bold text-muted mb-1">{{ __('menu.belum') }}</h4>
                        <p class="text-secondary">{{ __('menu.belum.desc') }}</p>
                    </div>
                </div>
            @endunless
        @else
            @foreach ($menus as $menu)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                    <x-menu-card-kantin-detail name="{{ $menu->name }}" price="{{ $menu->price }}"
                        isOpen="{{ $isOpen }}" id="{{ $menu->menu_item_id }}" image="{{ $menu->image }}"
                        isMerchant="{{ $isMerchant }}" />
                </div>
            @endforeach
        @endif
    </div>

    @auth
        <div class="modal fade" id="confirmAddModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-body text-center p-4">
                        <h4 class="fw-bold mb-3">{{ __('kantin.tambah.title') }}</h4>
                        <div id="selectedProductPreview" class="mb-3"></div>
                        <p class="fw-bold" id="selectedProductName"></p>
                        <p id="selectedProductPrice" class="text-primary fw-bold"></p>
                        <button class="btn w-100 mt-3 text-white" style="background-color: #4191E8"
                            id="confirmAddBtn">{{ __('kantin.tambah.true') }}</button>
                        <button class="btn btn-secondary w-100 mt-2"
                            data-bs-dismiss="modal">{{ __('kantin.tambah.false') }}</button>
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
                        <h4 class="fw-bold mt-3">{{ __('kantin.pesan.total') }}: <span id="checkoutTotal" class=""
                                style="color: #4191E8"></span></h4>
                        <button class="btn w-100 mt-3 text-white" style="background-color: #4191E8" id="confirmPaymentBtn">
                            <span id="confirmPaymentText">{{ __('kantin.pesan.lanjut') }}</span>
                            <span id="confirmPaymentSpinner" class="spinner-border spinner-border-sm d-none ms-2"
                                role="status"></span>
                        </button>
                        <button class="btn btn-secondary w-100 mt-2" id="cancelCheckoutBtn" data-bs-dismiss="modal">
                            {{ __('kembali') }}
                        </button>

                    </div>
                </div>
            </div>
        </div>
    @endauth
    </div>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        let cart = [];
        let selectedProduct = null;
        let isProcessing = false;
        let checkoutModal = null;
        const orderDetailRoute = "{{ route('order.detail', ['order' => ':invoice']) }}";
        const orderRemoveRoute = "{{ route('order.remove', ['id' => ':id']) }}";

        function lockCheckoutModal() {
            isProcessing = true;
            document.getElementById("confirmPaymentBtn").disabled = true;
            document.getElementById("cancelCheckoutBtn").disabled = true;
            document.getElementById("confirmPaymentSpinner").classList.remove("d-none");
            document.getElementById("confirmPaymentText").innerText = ".";
        }

        function unlockCheckoutModal() {
            isProcessing = false;
            document.getElementById("confirmPaymentBtn").disabled = false;
            document.getElementById("cancelCheckoutBtn").disabled = false;
            document.getElementById("confirmPaymentSpinner").classList.add("d-none");
            document.getElementById("confirmPaymentText").innerText = "{{ __('kantin.pesan.lanjut') }}";
        }

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
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                ${item.quantity <= 1 ? 'disabled' : ''}
                                onclick="updateQty(${item.id}, -1)">−</button>
                            <span class="px-2 fw-bold" style="color:#4191E8">
                                ${item.quantity}
                            </span>
                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                onclick="updateQty(${item.id}, 1)">+</button>
                        </div>
                    </div>
                </div>
                `;
            });
            document.getElementById("cartList").innerHTML = html;
            updateCheckoutButton();
        }

        function updateCheckoutButton() {
            const btn = document.getElementById('checkoutBtn');
            if (!btn) return;

            btn.disabled = cart.length === 0;
            btn.style.opacity = cart.length === 0 ? '0.6' : '1';
            btn.style.cursor = cart.length === 0 ? 'not-allowed' : 'pointer';
        }

        function selectProduct(name, price, image, id) {
            @if ($isMerchant || !$isOpen)
                return;
            @endif

            console.log(cart);
            const existing = cart.find(item => item.id === id);
            if (!existing) {
                selectedProduct = {
                    name,
                    price,
                    image,
                    id,
                    quantity: 1
                };
            } else selectedProduct = existing;
            document.getElementById("selectedProductName").innerText = name;
            document.getElementById("selectedProductPrice").innerText = "Rp " + price.toLocaleString();
            document.getElementById("selectedProductPreview").innerHTML =
                `<img src="/${image}" class="img-fluid rounded mb-2" style="max-height:150px; object-fit:cover;">`;
            new bootstrap.Modal(document.getElementById('confirmAddModal')).show();
        }

        function openCheckout() {
            if (cart.length === 0) {
                return;
            }

            let html = "",
                total = 0;
            cart.forEach(item => {
                total += item.price * item.quantity;
                html += `
                <div class="d-flex mb-3 align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="/${item.image}"
                            style="width:70px; height:70px; object-fit:cover; border-radius:10px; margin-right:12px;">
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

        document.addEventListener("DOMContentLoaded", () => {
            const merchantCard = document.getElementById('merchantCard');
            const orderCard = document.getElementById('orderCard');

            if (merchantCard && orderCard) {
                orderCard.style.maxHeight = merchantCard.offsetHeight + "px";
            }
        });

        document.getElementById("confirmAddBtn").addEventListener("click", () => {
            const existing = cart.find(item => item.id === selectedProduct.id);

            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({
                    id: selectedProduct.id,
                    name: selectedProduct.name,
                    price: Number(selectedProduct.price),
                    image: selectedProduct.image,
                    quantity: 1
                });

            }
            bootstrap.Modal.getInstance(document.getElementById('confirmAddModal')).hide();
            renderCart();
        });

        window.updateQty = function(itemId, change) {
            itemId = Number(itemId);

            const index = cart.findIndex(i => Number(i.id) === itemId);
            cart[index].quantity = Number(cart[index].quantity) + Number(change);

            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }

            renderCart();
        };

        document.getElementById('confirmCheckoutModal').addEventListener('hide.bs.modal', function(e) {
            if (isProcessing) {
                e.preventDefault();
            }
        });

        document.getElementById("confirmPaymentBtn").addEventListener("click", async () => {
            if (cart.length === 0) return;

            const btnConfirm = document.getElementById("confirmPaymentBtn");
            const btnCancel = document.getElementById("cancelCheckoutBtn");
            const text = document.getElementById("confirmPaymentText");
            const spinner = document.getElementById("confirmPaymentSpinner");
            const modalEl = document.getElementById("confirmCheckoutModal");
            const modal = bootstrap.Modal.getInstance(modalEl);

            lockCheckoutModal();

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch("{{ route('order.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({
                        merchant_id: {{ $merchant->merchant_id }},
                        items: cart
                    })
                });

                if (!response.ok) throw new Error("Gagal membuat pesanan");

                const data = await response.json();
                const trueRes = data.data;
                cart = [];
                renderCart();
                isProcessing = false;
                modal.hide();
                snap.pay(trueRes.snap_token, {
                    onSuccess: function(result) {
                        window.location.href = orderDetailRoute.replace(':invoice', trueRes
                            .invoice_number);
                    },
                    onPending: async function(result) {
                        await fetch(orderRemoveRoute.replace(':id', trueRes.order_id), {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token
                            }
                        });
                        unlockCheckoutModal();
                    },
                    onError: async function(result) {
                        await fetch(orderRemoveRoute.replace(':id', trueRes.order_id), {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token
                            }
                        });
                        unlockCheckoutModal();
                    }
                });
            } catch (error) {
                console.error(error);
                alert("Gagal membuat pesanan.");
                unlockCheckoutModal();
            }
        });
    </script>
@endsection
