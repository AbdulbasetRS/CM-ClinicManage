<?php

namespace Database\Factories;

use App\Enums\VisitStatus;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    protected $model = Visit::class;

    public function definition(): array
    {
        $patient = User::where('type', 'patient')->inRandomOrder()->first();
        $doctor = User::where('type', 'doctor')->inRandomOrder()->first();

        return [
            'patient_id' => $patient?->id ?? User::factory()->patient()->create()->id,
            'doctor_id' => $doctor?->id ?? User::factory()->doctor()->create()->id,
            'appointment_id' => Appointment::inRandomOrder()->first()?->id,
            'visit_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(VisitStatus::values()),
            'symptoms' => $this->faker->sentence,
            'diagnosis' => $this->faker->sentence,
            'treatment_plan' => $this->faker->sentence,
            'notes' => $this->faker->sentence,
            'created_by' => User::inRandomOrder()->value('id'),
            'updated_by' => $this->faker->boolean(50) ? User::inRandomOrder()->value('id') : null,
        ];
    }
}
