<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentFactory extends Factory
{
    protected $model = Treatment::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(),
            'notes' => $this->faker->optional()->paragraph(),
            'price' => $this->faker->numberBetween(500, 5000),
            'patient_id' => Patient::factory(),
        ];
    }
}
