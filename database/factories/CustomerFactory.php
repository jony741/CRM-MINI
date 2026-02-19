<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional(0.8)->phoneNumber(),
            'company' => fake()->optional(0.7)->company(),
            'address' => fake()->optional(0.6)->address(),
            'status' => fake()->randomElement(['active', 'inactive', 'lead', 'prospect']),
            'created_by' => User::factory(),
            'assigned_to' => fake()->optional(0.5)->randomElement(User::pluck('id')->toArray() ?: [null]),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function lead(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'lead',
        ]);
    }

    public function prospect(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'prospect',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
