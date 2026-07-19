<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'technician_id' => null,
            'status' => 'pending',
            'booking_date' => now(),
            'address' => fake()->address(),
            'notes' => fake()->sentence(),
            'shipping_fee' => fake()->numberBetween(0, 50000),
        ];
    }

    /**
     * Indicate that the booking is assigned to a technician.
     */
    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'assigned',
        ]);
    }

    /**
     * Indicate that the booking is in progress.
     */
    public function onProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'on_progress',
        ]);
    }

    /**
     * Indicate that the booking is done.
     */
    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'done',
        ]);
    }

    /**
     * Indicate that the booking is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
