<div class="card border-0 shadow-sm rounded-4 overflow-hidden"
    style="{{ $isOpen ? "cursor:pointer" : "cursor-none" }}; height:360px; display:flex; flex-direction:column;"
    @if (!$isMerchant && !$add) onclick="selectProduct('{{ $name }}', {{ $price }}, '{{ $image }}', '{{ $id }}')" @endif
    @if ($isMerchant && !$add) onclick="addMenu(false, '{{ $image }}', '{{ $name }}', {{ $price }}, '{{ $id }}')" @endif>
    @if ($add)
        <div class="card border-0 shadow rounded-4 d-flex align-items-center justify-content-center"
            style="flex: 1; background-color: #D9D9D9;">
            <button class="btn rounded-5 text-white d-flex align-items-center justify-content-center"
                onclick="event.stopPropagation(); addMenu()"
                style="background-color: #4191E8; width: 60px; height: 60px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-plus-icon lucide-plus">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
            </button>
        </div>
    @else
        <div style="height:220px; width:100%; overflow:hidden; position:relative;">
            @if (!empty($image))
                <img src="{{ asset($image) }}" class="w-100 h-100" style="object-fit:cover;">
            @else
                <div class="d-flex justify-content-center align-items-center w-100 h-100 bg-light">
                    <x-camera size="50" class="text-secondary" />
                </div>
            @endif
            @if ($isMerchant)
                <div class="position-absolute top-0 end-0 m-3 d-flex align-items-center justify-content-center rounded-circle shadow"
                    style="width:38px; height:38px; cursor:pointer; background-color:#4191E8;">
                    <x-pencil color="white" size="20" />
                </div>
            @endif
        </div>
        <div class="p-3 d-flex flex-column justify-content-between" style="height:200px;">
            <p class="fw-bold text-break fs-6 fs-md-5"
                style="color:#FB8C30; display:-webkit-box;
                    -webkit-line-clamp:3;
                    -webkit-box-orient:vertical;
                    overflow:hidden;">
                {{ $name }}
            </p>
            <button class="btn w-100 fw-bold rounded-pill text-white btn-sm" style="background-color:#4191E8;"
                onclick="event.stopPropagation(); selectProduct('{{ $name }}', {{ $price }}, '{{ $image }}', '{{ $id }}')">
                Rp. {{ number_format($price, 2, ',', '.') }}
            </button>
        </div>
    @endif
</div>
