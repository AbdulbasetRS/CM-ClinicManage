<?php

namespace Database\Factories;

use App\Enums\AttachmentType;
use App\Models\Attachment;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition(): array
    {
        $patient = User::where('type', 'patient')->inRandomOrder()->first();
        $doctor = User::where('type', 'doctor')->inRandomOrder()->first();

        return [
            'patient_id' => $patient?->id,
            'visit_id' => Visit::inRandomOrder()->first()?->id,
            'type' => $this->faker->randomElement(AttachmentType::values()),
            'name' => $this->faker->word.'.pdf',
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(),
            'uploaded_by' => $doctor?->id ?? User::factory()->doctor()->create()->id,
            'created_by' => User::inRandomOrder()->value('id'),
            'updated_by' => $this->faker->boolean(50) ? User::inRandomOrder()->value('id') : null,
        ];
    }
}
