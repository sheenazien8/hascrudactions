<?php

namespace Sheenazien8\Hascrudactions\Http\Controllers;

use Illuminate\Routing\Controller;
use Sheenazien8\Hascrudactions\Repositories\HascrudRepository;
use Sheenazien8\Hascrudactions\Http\Requests\Validation;
use Sheenazien8\Hascrudactions\Traits\HasCrudAction;

/**
 * Class HascrudController
 * @author sheenazien8
 */
class HascrudController extends Controller
{
    use HasCrudAction;

    protected $viewPath = 'hascrudactions::hascrud.main';

    protected $repositoryClass = HascrudRepository::class;

    protected $rules = Validation::class;
}
