<?php

namespace Database\Factories;

use App\Models\Dog;
use App\Services\DogGenome;
use Illuminate\Database\Eloquent\Factories\Factory;

class DogFactory extends Factory
{
    protected $model = Dog::class;

    public function definition(): array
    {
        $genome = new DogGenome();
        $phenotype = $genome->getPhenotype();

        return [
            'name' => fake()->firstName(),
            'breed' => fake()->randomElement([
                'Labrador Retriever',
                'German Shepherd',
                'Golden Retriever',
                'French Bulldog',
                'Beagle',
                'Poodle',
                'Rottweiler',
                'Yorkshire Terrier',
                'Boxer',
                'Dachshund'
            ]),
            'genome_data' => $genome->getAlleles(),
            'color_description' => $phenotype['description']
        ];
    }

    /**
     * Configure the model to be of a specific breed
     */
    public function breed(string $breed): static
    {
        return $this->state(fn (array $attributes) => [
            'breed' => $breed,
        ]);
    }

    /**
     * Configure the model to be solid colored
     */
    public function solid(): static
    {
        return $this->state(function (array $attributes) {
            $genome = new DogGenome([
                'base_color' => ['B', 'B'], // Black
                'white_spotting' => ['s', 's'], // Solid
                'dilution' => ['D', 'D'] // Normal
            ]);

            return [
                'genome_data' => $genome->getAlleles(),
                'color_description' => $genome->getPhenotype()['description']
            ];
        });
    }

    /**
     * Configure the model to be pied (colored with white patches)
     */
    public function pied(): static
    {
        return $this->state(function (array $attributes) {
            $genome = new DogGenome([
                'base_color' => ['B', 'B'], // Black
                'white_spotting' => ['S', 's'], // Pied
                'dilution' => ['D', 'D'] // Normal
            ]);

            return [
                'genome_data' => $genome->getAlleles(),
                'color_description' => $genome->getPhenotype()['description']
            ];
        });
    }
}

