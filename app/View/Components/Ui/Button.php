<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Button type
     */
    public string $type;

    /**
     * Button variant
     */
    public string $variant;

    /**
     * Additional classes
     */
    public string $class;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $type = 'button',
        string $variant = 'primary',
        string $class = ''
    ) {
        $this->type = $type;
        $this->variant = $variant;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.button');
    }
}
