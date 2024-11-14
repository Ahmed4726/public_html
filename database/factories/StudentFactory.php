<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'admission_session_id' => factory(\App\AdmissionSession::class),
        'name' => $faker->name,
        'department' => $faker->word,
        'roll' => $faker->word,
        'regestration' => $faker->word,
        'hall' => $faker->word,
    ];
});
