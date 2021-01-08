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
    public $withoutaction;

    /**
     * @var bool
     */
    public $withoutcheckbox;

    /**
     * @var bool
     */
    public $withoutcard;

    /**
     * @var bool
     */
    public $withoutTime;

    /**
     * @var bool
     */
    public $withoutbulk;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $title,
        array $thead = [],
        string $resources,
        bool $withoutaction = false,
        bool $withoutcheckbox = false,
        bool $withoutcard = false,
        bool $withoutTime = false,
        bool $withoutbulk = false
    ) {
        $this->title = $title;
        $this->resources = $resources;
        $this->withoutaction = $withoutaction;
        $this->withoutcheckbox = $withoutcheckbox;
        $this->withoutcard = $withoutcard;
        $this->withoutTime = $withoutTime;
        $this->withoutbulk = $withoutbulk;
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
