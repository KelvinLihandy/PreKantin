@php
    $rotation = match($direction) {
        'left' => 'rotate(0deg)',
        'right' => 'rotate(180deg)',
        'up' => 'rotate(-90deg)',
        'down' => 'rotate(90deg)',
        default => 'rotate(0deg)',
    };
@endphp

<svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" fill="none" 
     stroke="{{ $color }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
     class="{{ $class }}" style="transform: {{ $rotation }};">
    <path d="M6 8L2 12L6 16" />
    <path d="M2 12H22" />
</svg>
