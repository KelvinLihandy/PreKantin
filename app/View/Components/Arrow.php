<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Arrow extends Component
{
    public string $color;
    public int $size;
    public string $direction;
    public string $class;

    /**
     * Create a new component instance.
     *
     * @param string $color Icon color (default: white)
     * @param int $size Icon size in px (default: 24)
     *  @param int $direction Icon direction (default: left)
     * @param string $class Additional CSS classes
     */
    public function __construct(string $color = '#4191E8', int $size = 24, string $direction = 'left', string $class = '') {
        $this->color = $color;
        $this->size = $size;
        $this->direction = $direction;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.arrow');
    }
}
