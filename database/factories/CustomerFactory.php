<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name' => fake()->name(),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'primary_email' => fake()->unique()->safeEmail(),
            'uuid' => uuid(),
            'api_count' => rand(1,7),
            'created_by' => 1,
            'parent_customer_id' => 1,
            'is_reseller' => rand(0,1),
        ];

    }
}
