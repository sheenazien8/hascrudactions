<?php

namespace Sheenazien8\Hascrudactions\Tests;

use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\Concerns\WithFactories;

class TestingModel extends Model
{
    use WithFactories;

    protected $table = 'testing_tables';

    protected $fillable = ['name', 'salary', 'join_date'];
}
