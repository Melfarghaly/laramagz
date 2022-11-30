<?php

namespace App\View\Components\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public $title;
    public $currentActive;
    public $addLink;

    /**
     * Create a new component instance.
     *
     * @param $title
     * @param $currentActive
     * @param $addLink
     */
    public function __construct($title, $currentActive, $addLink = [])
    {
        $this->title = $title;
        $this->currentActive = $currentActive;
        $this->addLink = $addLink;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('components.admin.breadcrumbs');
    }
}
