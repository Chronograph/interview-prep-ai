<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class NavLink extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $href = '#',
        public bool $active = false
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.nav-link');
    }
}