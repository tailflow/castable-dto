<?php

use Faker\Generator as Faker;
use Tailflow\DataTransferObjects\Tests\fixtures\app\Models\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address' => [
            'country' => $faker->countryCode,
            'city' => $faker->city,
            'street' => $faker->streetAddress
        ]
    ];
});