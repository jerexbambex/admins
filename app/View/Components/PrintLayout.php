<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PrintLayout extends Component
{
    public $title, $landscape;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title = '', $landscape = 'false')
    {
        $this->title = $title;
        $this->landscape = $landscape;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.print-layout', [
            'title', $this->title,
            'landscape' => $this->landscape
        ]);
    }
}
