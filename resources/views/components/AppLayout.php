<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // This tells Laravel: "When I use <x-app-layout>, 
        // go find the file resources/views/layouts/app.blade.php"
        return view('layouts.app');
    }
}