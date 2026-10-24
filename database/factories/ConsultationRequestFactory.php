<?php

namespace Database\Factories;

use App\Models\ConsultationRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConsultationRequest>
 */
class ConsultationRequestFactory extends Factory
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
            'email' => fake()->safeEmail(),
            'preferred_contact' => 'email',
            'topic' => 'general',
            'consent' => true,
            'status' => 'new',
            'submitted_at' => now(),
        ];
    }
}
