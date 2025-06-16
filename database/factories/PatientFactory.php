<?php

namespace Database\Factories;

use App\Models\Owner;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'type' => $this->faker->randomElement(['cat', 'dog', 'rabbit']),
            'date_of_birth' => $this->faker->dateTimeBetween('-5 years', '-6 months'),
            'owner_id' => Owner::factory(),
        ];
    }
}
