<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AdmissionSession;
use Faker\Generator as Faker;

$factory->define(AdmissionSession::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
