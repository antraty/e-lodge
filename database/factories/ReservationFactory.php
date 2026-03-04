<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_debut' => $this->faker->date(),
            'date_fin' => $this->faker->date(),
            'status' => $this->faker->randomElement(['confirmée', 'en attente', 'annulée', 'terminée']),
            'chambre_id' => \App\Models\Chambre::factory(),
            'client_id' => \App\Models\Client::factory(),
        ];
    }
}
