<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Alert type
     */
    public string $type;

    /**
     * Alert message
     */
    public string $message;

    /**
     * Alert title
     */
    public ?string $title;

    /**
     * Additional classes
     */
    public string $class;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $type = 'info',
        string $message = '',
        ?string $title = null,
        string $class = ''
    ) {
        $this->type = $type;
        $this->message = $message;
        $this->title = $title;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.alert');
    }
}
