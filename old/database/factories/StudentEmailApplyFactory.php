<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StudentEmailApply;
use Faker\Generator as Faker;

$factory->define(StudentEmailApply::class, function (Faker $faker) {
    return [
        'admission_session_id' => factory(\App\AdmissionSession::class),
        'department_id' => factory(\App\Department::class),
        'program_id' => factory(\App\Program::class),
        'hall_id' => factory(\App\Hall::class),
        'regestration_number' => $faker->word,
        'name' => $faker->name,
        'username' => $faker->userName,
        'contact_phone' => $faker->word,
        'existing_email' => $faker->word,
        'image' => $faker->word,
    ];
});
