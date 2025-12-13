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

                    <div id="merchantImagePreview" class="w-100 h-100 position-absolute top-0 start-0">
                        @if ($imageExist)
                            <img src="{{ $imageUrl }}" alt="Merchant Image" class="rounded-top rounded-4 w-100 h-100"
                                style="object-fit:cover;">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <x-camera size="96" class="text-gray-500" />
                            </div>
                        @endif
                    </div>

                    @if ($isMerchant)
                        <form id="merchantImageForm" action="{{ route('merchant.image') }}" method="POST"
                            enctype="multipart/form-data" class="d-none">
                            @csrf
                            <input type="hidden" name="merchant_id" value="{{ $merchant->merchant_id }}">
                            <input type="file" id="merchantImageInput" name="image" accept="image/jpeg,image/png">
                        </form>
                        <div id="editImageBtn"
                            class="position-absolute top-0 end-0 m-4 d-flex align-items-center justify-content-center rounded-circle"
                            style="width:50px; height:50px; cursor:pointer; background-color:#4191E8; z-index:10;"
                            onclick="openImagePicker()">
                            <x-pencil color="white" />
                        </div>
                        <div id="loadingImageBtn"
                            class="position-absolute top-0 end-0 m-4 d-none align-items-center justify-content-center rounded-circle"
                            style="width:50px; height:50px; background-color:#4191E8; z-index:10;">
                            <span class="spinner-border spinner-border-sm text-white" role="status"></span>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <form id="merchantNameForm" action="{{ route('merchant.name') }}" method="POST"
                                class="d-flex align-items-center">
                                @csrf
                                <input type="hidden" name="merchant_id" value="{{ $merchant->merchant_id }}">
                                <h3 id="merchantName" class="fw-bold mb-0 me-3">{{ $merchant->user->name }}</h3>
                                <input type="text" id="merchantNameInput" name="name"
                                    class="form-control form-control-lg fw-bold me-4 d-none"
                                    value="{{ $merchant->user->name }}" style="max-width: 300px;" required>

                                @if ($isMerchant)
                                    <span id="editNameBtn" style="cursor: pointer;" onclick="toggleEditName(event)">
                                        <x-pencil color="black" />
                                    </span>
                                    <button type="submit" id="saveNameBtn" class="d-none btn btn-link p-0 border-0"
                                        style="cursor: pointer;">
                                        <x-pencil color="#4191E8" />
                                    </button>
                                @endif
                            </form>
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
                        <p id="merchantHours" class="mb-0 me-2" style="color:#4191E8">
                            {{ substr($merchant->open, 0, 5) ?? '00:00' }} -
                            {{ substr($merchant->close, 0, 5) ?? '00:00' }}
                        </p>

                        <form id="merchantHoursForm" action="{{ route('merchant.time') }}" method="POST"
                            class="d-none align-items-center gap-2">
                            @csrf
                            <input type="hidden" name="merchant_id" value="{{ $merchant->merchant_id }}">
                            <input type="time" id="openTime" name="open" class="form-control form-control-sm"
                                value="{{ substr($merchant->open, 0, 5) }}" style="width: 100px;" required>
                            <span>-</span>
                            <input type="time" id="closeTime" name="close" class="form-control form-control-sm"
                                value="{{ substr($merchant->close, 0, 5) }}" style="width: 100px;" required>
                            <button type="submit" id="saveHoursBtn" class="btn btn-link p-0 border-0">
                                <x-pencil color="#4191E8" size="20" />
                            </button>
                        </form>

                        @if ($isMerchant)
                            <span id="editHoursBtn" style="cursor: pointer;" onclick="toggleEditHours()">
                                <x-pencil color="black" size="20" />
                            </span>
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
                        id="{{ $menu->menu_item_id }}" image="{{ $menu->image_url }}"
                        isMerchant="{{ $isMerchant }}" />
                </div>
            @endforeach
        @endif
    </div>

    @auth
        <div class="modal fade" id="confirmAddModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-body text-center p-4">
                        <h4 class="fw-bold mb-3">{{ __('kantin.tambah.title') }}</h4>
                        <div id="selectedProductPreview" class="mb-3"></div>
                        <p class="fw-bold" id="selectedProductName"></p>
                        <p id="selectedProductPrice" class="text-primary fw-bold"></p>
                        <button class="btn w-100 mt-3 text-white fw-bold" style="background-color: #4191E8"
                            id="confirmAddBtn">{{ __('kantin.tambah.true') }}</button>
                        <button class="btn btn-secondary w-100 mt-2"
                            data-bs-dismiss="modal">{{ __('kantin.tambah.false') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmCheckoutModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
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

        @if (Auth::user()->role->name === 'Merchant')
            <div class="modal fade" id="kantinModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-body p-4">
                            <form id="addMenuForm" action="{{ route('menu.add') }}" method="POST"
                                enctype="multipart/form-data" onsubmit="handleMenuSubmit(event)">
                                @csrf
                                <input type="hidden" name="merchant_id" value="{{ $merchant->merchant_id }}" />
                                <input type="hidden" id="menuIdInput" name="menu_item_id"
                                    value="{{ old('menu_item_id') }}" />
                                <div class="mb-3 w-100">
                                    <input type="file" id="menuImageInput" name="image" accept="image/*"
                                        class="d-none">
                                    <div id="menuImage" class="rounded-4 w-100"
                                        style="height:300px; background-color:#e0e0e0; cursor:pointer; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96"
                                            viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="text-gray-500">
                                            <path
                                                d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z" />
                                            <circle cx="12" cy="13" r="3" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-lg border-0 text-white"
                                        placeholder="{{ __('modal.menu') }}" name="nama_menu" id="menuName"
                                        value="{{ old('nama_menu') }}"
                                        style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;" required>
                                    @error('nama_menu')
                                        <div class="small text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white border-0" id="basic-addon1"
                                            style="color:#4191E8; font-weight:bold;">Rp</span>
                                        <input type="number" class="form-control border-0 text-white"
                                            placeholder="{{ __('modal.harga') }}" name="harga" id="menuPriceInput"
                                            value="{{ old('harga') }}"
                                            style="background-color: rgba(65, 145, 232, 0.75); caret-color: white;" required>
                                    </div>
                                    @error('harga')
                                        <div class="small text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" id="submitMenu" class="btn w-100 fw-bold text-white mb-2"
                                    style="background-color: #4191E8">
                                </button>
                                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">
                                    {{ __('kantin.tambah.false') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
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
        @if (session('error'))
            <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {!! implode('<br>', $errors->all()) !!}
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>
    </div>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        let cart = [];
        let selectedImageFile = null;
        let isImageSelected = false;
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

        function openImagePicker() {
            const imageInput = document.getElementById('merchantImageInput');
            if (!imageInput) return;

            imageInput.click();
        }

        function saveImage() {
            if (!selectedImageFile || !isImageSelected) {
                return;
            }

            const editBtn = document.getElementById('editImageBtn');
            const loadingBtn = document.getElementById('loadingImageBtn');

            if (!editBtn || !loadingBtn) return;

            editBtn.classList.add('d-none');
            loadingBtn.classList.remove('d-none');
            loadingBtn.classList.add('d-flex');

            isImageSelected = false;

            document.getElementById('merchantImageForm')?.submit();
        }

        function cancelImageEdit() {
            const preview = document.getElementById('merchantImagePreview');
            const imageInput = document.getElementById('merchantImageInput');

            if (preview) {
                preview.innerHTML = `
                @if ($imageExist)
                    <img src="{{ $imageUrl }}" alt="Merchant Image" class="rounded-top rounded-4 w-100 h-100"
                        style="object-fit:cover;">
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                        <x-camera size="96" class="text-gray-500" />
                    </div>
                @endif
            `;
            }

            if (imageInput) imageInput.value = '';
            selectedImageFile = null;
            isImageSelected = false;
        }

        function toggleEditHours() {
            const hoursDisplay = document.getElementById('merchantHours');
            const hoursForm = document.getElementById('merchantHoursForm');
            const editBtn = document.getElementById('editHoursBtn');

            if (!hoursDisplay || !hoursForm || !editBtn) return;

            hoursDisplay.classList.add('d-none');
            hoursForm.classList.remove('d-none');
            hoursForm.classList.add('d-flex');
            editBtn.classList.add('d-none');

            document.getElementById('openTime')?.focus();
        }

        function cancelEditHours() {
            const hoursDisplay = document.getElementById('merchantHours');
            const hoursForm = document.getElementById('merchantHoursForm');
            const editBtn = document.getElementById('editHoursBtn');

            if (!hoursDisplay || !hoursForm || !editBtn) return;

            hoursDisplay.classList.remove('d-none');
            hoursForm.classList.remove('d-flex');
            hoursForm.classList.add('d-none');
            editBtn.classList.remove('d-none');

            document.getElementById('openTime').value = '{{ substr($merchant->open, 0, 5) }}';
            document.getElementById('closeTime').value = '{{ substr($merchant->close, 0, 5) }}';
        }

        function showHoursLoadingSpinner() {
            const saveBtn = document.getElementById('saveHoursBtn');
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm" style="color: #4191E8" role="status"></span>
            `;
            }
        }

        function validateHours() {
            const openTime = document.getElementById('openTime');
            const closeTime = document.getElementById('closeTime');

            if (!openTime.value || !closeTime.value) {
                alert('Please select both opening and closing time');
                return false;
            }

            if (openTime.value >= closeTime.value) {
                alert('Closing time must be after opening time');
                return false;
            }

            return true;
        }

        function showNameLoadingSpinner() {
            const saveBtn = document.getElementById('saveNameBtn');
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm" style="color:#4191E8" role="status"></span>
                `;
            }
        }

        function doNameTrim() {
            const nameInput = document.getElementById('merchantNameInput');

            if (!nameInput.value.trim()) {
                e.preventDefault();
                alert('Name cannot be empty');
                return false;
            }
        }

        function toggleEditName() {
            const nameDisplay = document.getElementById('merchantName');
            const nameInput = document.getElementById('merchantNameInput');
            const editBtn = document.getElementById('editNameBtn');
            const saveBtn = document.getElementById('saveNameBtn');

            nameDisplay.classList.add('d-none');
            nameInput.classList.remove('d-none');
            editBtn.classList.add('d-none');
            saveBtn.classList.remove('d-none');

            nameInput.focus();
            nameInput.select();
        }

        function confirmSave() {
            doNameTrim();
            showNameLoadingSpinner();
            return true;
        }

        function addMenu(add = true, image = null, name = "", price = null, id = null) {
            @if (!$isMerchant)
                return;
            @endif

            const imageEle = document.getElementById("menuImage");
            const imageInput = document.getElementById("menuImageInput");
            const submit = document.getElementById("submitMenu");
            const menuNameInput = document.getElementById("menuName");
            const menuPriceInput = document.getElementById("menuPriceInput");
            const menuIdInput = document.getElementById("menuIdInput");

            submit.innerText = add ? "{{ __('modal.add') }}" : "{{ __('modal.edit') }}";
            menuNameInput.value = name;
            menuPriceInput.value = price;
            menuIdInput.value = id;

            if (add || !image) {
                imageEle.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none"
                stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="text-gray-500" style="cursor:pointer;">
                <path d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z"/>
                <circle cx="12" cy="13" r="3"/>
            </svg>
        `;
                imageInput.value = "";
            } else {
                imageEle.innerHTML = `
            <img src="${image}" style="width:100%; height:100%; object-fit:cover; cursor:pointer;">
        `;
            }

            imageEle.onclick = () => imageInput.click();

            imageInput.onchange = () => {
                const file = imageInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        imageEle.innerHTML = `
                    <img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover; cursor:pointer;">
                `;
                        imageEle.onclick = () => imageInput.click();
                    };
                    reader.readAsDataURL(file);
                } else {
                    imageEle.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none"
                    stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-gray-500" style="cursor:pointer;">
                    <path d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z"/>
                    <circle cx="12" cy="13" r="3"/>
                </svg>
            `;
                    imageEle.onclick = () => imageInput.click();
                }
            };

            new bootstrap.Modal(document.getElementById('kantinModal')).show();
        }

        function handleMenuSubmit(event) {
            event.preventDefault();

            const submitBtn = document.getElementById("submitMenu");
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            `;

            event.target.submit();
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

            var uploadToastEl = document.getElementById('uploadToast');
            if (uploadToastEl) {
                var uploadToast = new bootstrap.Toast(uploadToastEl, {
                    delay: 10000
                });
                uploadToast.show();
            }

            var errorToastEl = document.getElementById('errorToast');
            if (errorToastEl) {
                var errorToast = new bootstrap.Toast(errorToastEl, {
                    delay: 10000
                });
                errorToast.show();
            }
        });

        document.getElementById('merchantImageInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (!['image/jpeg', 'image/png'].includes(file.type)) {
                alert('Only JPEG and PNG images are allowed');
                this.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Image size must be less than 2MB');
                this.value = '';
                return;
            }

            selectedImageFile = file;
            isImageSelected = true;

            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('merchantImagePreview');
                if (preview) {
                    preview.innerHTML = `
                    <img src="${event.target.result}" alt="Preview" class="rounded-top rounded-4 w-100 h-100"
                        style="object-fit:cover;">
                `;
                }
            };
            reader.readAsDataURL(file);
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && isImageSelected) {
                e.preventDefault();
                saveImage();
            } else if (e.key === 'Escape' && isImageSelected) {
                e.preventDefault();
                cancelImageEdit();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const saveBtn = document.getElementById('saveImageBtn');
                if (saveBtn && !saveBtn.classList.contains('d-none')) {
                    cancelImageEdit();
                }
            }
        });

        document.getElementById('merchantHoursForm')?.addEventListener('submit', function(e) {
            doNameTrim();
            showHoursLoadingSpinner();
        });

        document.getElementById('openTime')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();

                if (!validateHours()) {
                    return false;
                }

                showHoursLoadingSpinner();

                setTimeout(() => {
                    document.getElementById('merchantHoursForm')?.submit();
                }, 100);
            }
        });

        document.getElementById('closeTime')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();

                if (!validateHours()) {
                    return false;
                }

                showHoursLoadingSpinner();

                setTimeout(() => {
                    document.getElementById('merchantHoursForm')?.submit();
                }, 100);
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const hoursForm = document.getElementById('merchantHoursForm');
                if (hoursForm && !hoursForm.classList.contains('d-none')) {
                    cancelEditHours();
                }
            }
        });

        document.getElementById('merchantNameForm')?.addEventListener('submit', function(e) {
            const nameInput = document.getElementById('merchantNameInput');

            if (!nameInput.value.trim()) {
                e.preventDefault();
                alert('Name cannot be empty');
                return false;
            }

            showNameLoadingSpinner()

        });

        document.getElementById('merchantNameInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const nameInput = document.getElementById('merchantNameInput');
                const saveBtn = document.getElementById('saveNameBtn');

                if (!nameInput.value.trim()) {
                    e.preventDefault();
                    alert('Name cannot be empty');
                    return false;
                }

                if (saveBtn) {
                    saveBtn.disabled = true;
                    saveBtn.innerHTML = `
                <span class="spinner-border spinner-border" style="color:#4191E8;" role="status"></span>
            `;
                }

                document.getElementById('merchantNameForm').submit();
            }
        });

        document.getElementById('merchantNameInput')?.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const nameDisplay = document.getElementById('merchantName');
                const nameInput = document.getElementById('merchantNameInput');
                const editBtn = document.getElementById('editNameBtn');
                const saveBtn = document.getElementById('saveNameBtn');

                nameInput.value = nameDisplay.textContent.trim();
                nameDisplay.classList.remove('d-none');
                nameInput.classList.add('d-none');
                editBtn.classList.remove('d-none');
                saveBtn.classList.add('d-none');
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