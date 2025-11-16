<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FavMenuCard extends Component
{
    public string $image;
    public string $name;
    public string $merchant;
    public int $price;
    public string $class;

    /**
     * Create a new component instance.
     *
     * @param string $color Icon color (default: white)
     * @param int $size Icon size in px (default: 24)
     * @param string $class Additional CSS classes
     */
    public function __construct(string $image, string $name, string $merchant, int $price, string $class = '')
    {
        $this->image = $image;
        $this->name = $name;
        $this->merchant = $merchant;
        $this->price = $price;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.fav-menu-card');
    }
}
