<?php

namespace Database\Factories;

use App\Enums\BloodType;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{

    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->optional()->firstName,
            'last_name' => $this->faker->lastName,
            'whatapp_number' => '01' . $this->faker->numberBetween(100000000, 999999999),
            'telegram_number' => '01' . $this->faker->numberBetween(100000000, 999999999),
            'date_of_birth' => $this->faker->optional()->date('Y-m-d', '2005-01-01'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'avatar' => null,
            'title' => $this->faker->optional()->jobTitle,
            'address' => $this->faker->optional()->address,
            'blood_type' => $this->faker->randomElement(BloodType::values()),
            'chronic_diseases' => $this->faker->optional()->sentence,
            'allergies' => $this->faker->optional()->sentence,
            'note' => $this->faker->optional()->sentence,
            'created_by' => User::inRandomOrder()->value('id'),
            'updated_by' => $this->faker->boolean(50) ? User::inRandomOrder()->value('id') : null,
        ];
    }
}