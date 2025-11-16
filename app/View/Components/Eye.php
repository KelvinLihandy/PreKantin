<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Eye extends Component
{
    public string $color;
    public int $size;
    public string $class;

    /**
     * Create a new component instance.
     *
     * @param string $color Icon color (default: white)
     * @param int $size Icon size in px (default: 24)
     * @param string $class Additional CSS classes
     */
    public function __construct(string $color = 'white', int $size = 24, string $class = '')
    {
        $this->color = $color;
        $this->size = $size;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.eye');
    }
}
