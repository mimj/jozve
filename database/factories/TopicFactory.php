<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Topic;
use Faker\Generator as Faker;

$factory->define(Topic::class, function (Faker $faker) {
    return [
        'title' => $faker->text(30),
        'user_id' => mt_rand(1, 2),
        'unique_name'=>\App\Http\Controllers\TopicsController::random_strings(10)

    ];
});
