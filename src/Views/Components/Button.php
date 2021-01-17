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
     * @var string $to
     */
    public $to;

    public function __construct(
        $to = '',
        $color = 'primary',
        $size = 'md',
        $block = false,
        $title = ''
    ) {
        $this->to = $to;
        $this->color = $color;
        $this->size = $size;
        $this->block = $block;
        $this->title = $title;
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
