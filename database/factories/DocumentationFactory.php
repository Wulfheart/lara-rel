<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\documentation;
use Faker\Generator as Faker;

$factory->define(documentation::class, function (Faker $faker) {
    return [
        'customer' => $faker->company
    ];
});
