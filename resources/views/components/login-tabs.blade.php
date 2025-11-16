<div>
    <div class="nav nav-tabs {{ $class ?? '' }}" role="tablist" style="border-bottom: none;">
        @foreach ($items as $item)
            <button class="nav-link fw-semibold {{ $item['active'] ? 'active' : '' }}"
                id="{{ $item['id'] }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $item['id'] }}" 
                type="button" role="tab" aria-controls="{{ $item['id'] }}"
                aria-selected="{{ $item['active'] ? 'true' : 'false' }}">
                {{ $item['label'] }}
            </button>
        @endforeach
    </div>

    <style>
        .nav-tabs .nav-link {
            border: none !important;
            color: #6c757d;
            position: relative;
            padding-bottom: 10px;
            transition: color .2s ease;
        }

        .nav-tabs  {
        }

        .nav-tabs .nav-link.active {
            color: #4191E8 !important;
            font-weight: 600;
        }

        .nav-tabs .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background-color: #4191E8;
            border-radius: 2px;
        }
    </style>
</div>
