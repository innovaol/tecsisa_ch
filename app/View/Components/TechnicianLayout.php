<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class TechnicianLayout extends Component
{
    public $hideNav;
    public $hideHeader;

    public function __construct($hideNav = false, $hideHeader = false)
    {
        $this->hideNav = $hideNav;
        $this->hideHeader = $hideHeader;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.technician');
    }
}
