<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'entry_time' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'exit_time' => $this->faker->optional(0.7)->dateTimeBetween('now', '+1 week'),
            'duration' => $this->faker->optional(0.7)->numberBetween(30, 360),
            'amount' => $this->faker->optional(0.7)->randomFloat(2, 5, 50),
            'folio' => $this->faker->optional(0.5)->uuid(),
        ];
    }
}
