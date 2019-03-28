<?php

use App\Models\Team;
use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Team::class, static function (Faker $faker): array {
    return [
        'title' => $faker->company,
    ];
});
