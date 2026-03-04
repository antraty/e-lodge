<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paiement>
 */
class PaiementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_paiement'=>$this->faker->date(),
            'montant'=>$this->faker->randomFloat(2, 50, 100),
            'mode_paiement'=>$this->faker->randomElement(['carte', 'mobile', 'espèces', 'en ligne']),
            'reservation_id'=>\App\Models\Reservation::factory(),'facture_id' => \App\Models\Facture::factory(),
        ];
    }
}
