<?php

namespace App\View\Components\Iframes\Maps;

use App\Traits\IframesRoutesTrait;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Type3 extends Component
{
    use IframesRoutesTrait;
    /**
     * Create a new component instance.
     */
    public function __construct(public $investment)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.iframes.maps.type3');
    }
}
