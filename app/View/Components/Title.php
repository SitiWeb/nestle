<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Title extends Component
{
    public $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function render()
    {
        return view('components.title');
    }
}
