<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facture>
 */
class FactureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_facture' => $this->faker->date(),
            'montant_total' => $this->faker->randomFloat(2, 50, 1000),
            'reservation_id' => \App\Models\Reservation::factory(),
        ];
    }
}
