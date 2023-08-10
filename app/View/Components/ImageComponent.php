<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImageComponent extends Component
{
    public $src;
    public $class;
    public $style;

    public function __construct($src, $class = '', $style = '')
    {
        $this->src = $src;
        $this->class = $class;
        $this->style = $style;
    }

    public function render()
    {
        return view('components.image-component');
    }
}
