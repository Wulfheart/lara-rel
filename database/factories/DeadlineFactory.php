<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\deadline;
use Faker\Generator as Faker;

$factory->define(deadline::class, function (Faker $faker) {
    return [
        'documentation_id' => 1,
        'due_until' => $faker->dateTimeBetween($startDate = '-10days', $endDate = '+30days'),
        'finished_at' => ($faker->boolean) ? $faker->dateTimeBetween($startDate = '-10days', $endDate = '+30days') : null
    ];
});
