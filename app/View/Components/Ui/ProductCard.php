<?php

namespace App\View\Components\Ui;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    /**
     * Product
     */
    public Product $product;

    /**
     * Additional classes
     */
    public string $class;

    /**
     * Create a new component instance.
     */
    public function __construct(
        Product $product,
        string $class = ''
    ) {
        $this->product = $product;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.product-card');
    }
}
