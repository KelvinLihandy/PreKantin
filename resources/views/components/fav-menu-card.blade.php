<div class="card border-0 shadow-sm rounded rounded-4">
    <img src="{{ $image }}" class="card-img-top rounded-top rounded-4" style="height:300px" alt="Bakmi">
    <div class="card-body">
        <h5 class="fw-bold"
            style="color:#FB8C30;
                display:-webkit-box;
                -webkit-line-clamp:2;
                -webkit-box-orient:vertical;
                overflow:hidden;
                min-height:48px;">
            {{ $name }}
        </h5>

        <p class="mb-1 small text-muted">
            {{ __('home.fav.by') }}: <span style="color: #4191E8">{{ $merchant }}</span>
        </p>
        <p class="fw-bold text-dark">Rp {{ number_format($price, 2, ',', '.') }}</p>
    </div>
</div>
