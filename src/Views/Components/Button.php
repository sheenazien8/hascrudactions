<?php

namespace Sheenazien8\Hascrudactions\Views\Components;

use Illuminate\View\Component;

class Button extends Component
{
    /**
     * @var string $color
     */
    public $color;
    /**
     * @var string $size
     */
    public $size;
    /**
     * @var string $block
     */
    public $block;
    /**
     * @var string $block
     */
    public $title;
    /**
     * @var string $icon
     */
    public $icon;
    /**
     * @var string $to
     */
    public $to;
    /**
     * @var string $submitIcon
     */
    public $submitIcon;

    public function __construct(
        $to = '',
        $color = 'primary',
        $size = 'md',
        $block = false,
        $title = '',
        $icon = '',
        $submitIcon = false
    ) {
        $this->to = $to;
        $this->color = $color;
        $this->size = $size;
        $this->block = $block;
        $this->title = $title;
        $this->icon = $icon;
        $this->submitIcon = $submitIcon;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('hascrudactions::components.button');
    }
}
