<?php

namespace Database\Factories;

use App\Models\Endereco;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Endereco>
 */
class EnderecoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cliente_id' => \App\Models\Cliente::factory(),
            'rua' => $this->faker->streetName(),
            'numero' => $this->faker->buildingNumber(),
            'cidade' => 'Rio de Janeiro',
            'bairro' => $this->faker->citySuffix(),
        ];
    }
}
