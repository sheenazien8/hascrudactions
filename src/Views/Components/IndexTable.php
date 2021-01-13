<?php

namespace Sheenazien8\Hascrudactions\Views\Components;

use Illuminate\View\Component;

class IndexTable extends Component
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var array
     */
    public $thead;

    /**
     * @var string
     */
    public $resources;

    /**
     * @var bool
     */
    public $withoutAction;

    /**
     * @var bool
     */
    public $withoutCheckbox;

    /**
     * @var bool
     */
    public $withoutCard;

    /**
     * @var bool
     */
    public $withoutTime;

    /**
     * @var bool
     */
    public $withoutBulk;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $title,
        array $thead = [],
        string $resources,
        bool $withoutAction = false,
        bool $withoutCheckbox = false,
        bool $withoutCard = false,
        bool $withoutTime = false,
        bool $withoutBulk = false
    ) {
        $this->title = $title;
        $this->resources = $resources;
        $this->withoutAction = $withoutAction;
        $this->withoutCheckbox = $withoutCheckbox;
        $this->withoutCard = $withoutCard;
        $this->withoutTime = $withoutTime;
        $this->withoutBulk = $withoutBulk;
        $this->thead = $thead;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('hascrudactions::components.index-table');
    }
}
