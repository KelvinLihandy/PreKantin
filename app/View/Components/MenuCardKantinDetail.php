<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuCardKantinDetail extends Component
{
    public $name;
    public $price;
    public $image;
    public $id;
    public $add;
    public $isMerchant;
    public $isOpen;

    public function __construct($name = "", $price = 0, $image = null, $id = null, $add = false, $isMerchant = false, $isOpen = true)
    {
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
        $this->id = $id;
        $this->add = $add;
        $this->isMerchant = $isMerchant;
        $this->isOpen = $isOpen;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.menu-card-kantin-detail');
    }
}
