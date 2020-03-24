<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Article;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'uuid' => (string) Str::uuid(),
        'user_id' => function(){
            return User::all()->random();
        },
        'title' => $title = $faker->unique()->sentence,
        'slug' => Str::slug($title),
        'description' => $faker->realText(1000),
        'status' => 1,
        'created_at' => $created = $faker->dateTimeBetween('-2 years', '-2 months'),
        'updated_at' => $faker->dateTimeBetween($created, strtotime('+2 days'))
    ];
});
