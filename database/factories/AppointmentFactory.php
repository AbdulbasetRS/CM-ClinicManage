<?php

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $patient = User::where('type', 'patient')->inRandomOrder()->first();

        return [
            'patient_id' => $patient?->id ?? User::factory()->patient()->create()->id,
            'date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'status' => AppointmentStatus::SCHEDULED->value,
            'notes' => $this->faker->sentence,
            'created_by' => User::inRandomOrder()->value('id'),
            'updated_by' => $this->faker->boolean(50) ? User::inRandomOrder()->value('id') : null,
        ];
    }
}
