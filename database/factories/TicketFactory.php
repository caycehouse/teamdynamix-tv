<?php

use App\Ticket;
use Faker\Generator;

$factory->define(Ticket::class, function (Generator $faker) {
    return [
        'ticket_id' => $faker->randomNumber(8),
        'title' => $faker->sentence,
        'lab' => $faker->numerify($faker->word.' ###'),
        'status' => $faker->randomElement(['New', 'Work-in-Progress', 'On Hold', 'Closed', 'Cancelled']),
        'age' => $faker->numberBetween(0, 1000),
    ];
});
