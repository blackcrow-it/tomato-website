<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Post::class, function (Faker $faker) {
    $users = User::all();

    return [
        'title' => $faker->realText(rand(10, 255)),
        'slug' => $faker->slug(),
        'thumbnail' => $faker->imageUrl(),
        'cover' => $faker->imageUrl(),
        'description' => $faker->realText(rand(100, 255)),
        'content' => $faker->realText(rand(10, 2550)),
        'view' => rand(0, 1000),
        'enabled' => rand(0, 1) == 1,
        'meta_title' => $faker->realText(rand(10, 255)),
        'meta_description' => $faker->realText(rand(10, 255)),
        'og_title' => $faker->realText(rand(10, 255)),
        'og_description' => $faker->realText(rand(10, 255)),
        'og_image' => $faker->imageUrl(),
    ];
});
