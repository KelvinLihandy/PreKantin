<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LoginTabs extends Component
{
    public $items;
    public $class;

    public function __construct($items = [], $class = '')
    {
        $this->items = $items;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.login-tabs');
    }
}