<?php

namespace Database\Factories;

use App\Models\DamageType;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DamageType>
 */
class DamageTypeFactory extends Factory
{
    protected $model = DamageType::class;

    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(50000, 500000),
            'is_active' => true,
        ];
    }
}
