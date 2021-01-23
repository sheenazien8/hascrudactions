<?php

namespace Sheenazien8\Hascrudactions\Tests\database\factories;

use Faker\Generator as Faker;
use Sheenazien8\Hascrudactions\Tests\Models\TestingModel;

$factory->define(TestingModel::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'salary' => $faker->numberBetween(0000, 9999),
        'join_date' => $faker->date()
    ];
});
