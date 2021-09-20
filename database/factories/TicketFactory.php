<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ticket_id' => $this->faker->randomNumber(8),
            'title' => $this->faker->sentence,
            'lab' => $this->faker->numerify($this->faker->word.' ###'),
            'status' => $this->faker->randomElement(['New', 'Work-in-Progress', 'On Hold', 'Closed', 'Cancelled']),
            'age' => $this->faker->numberBetween(0, 1000),
            'resp_group' => $this->faker->sentence(3),
        ];
    }
}
