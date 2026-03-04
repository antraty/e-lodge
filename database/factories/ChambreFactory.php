<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chambre>
 */
class ChambreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero'=>$this->faker->unique()->numberBetween(1, 500),            
            'type'=>$this->faker->randomElement(['simple', 'double','suite']),        
            'status'=>$this->faker->randomElement(['libre', 'occupé']),
            'prix'=>$this->faker->randomFloat(2, 20, 200)          
        ];
    }
}
