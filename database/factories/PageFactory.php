<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Page;
use Faker\Generator as Faker;

$factory->define(Page::class, function (Faker $faker) {
    return [
        'title' => $faker->text(60),
        'topic_id' => mt_rand(1, 5),
        'position' => mt_rand(1, 18),
        'unique_name'=>\App\Http\Controllers\PagesController::random_strings(10)
    ];
});
