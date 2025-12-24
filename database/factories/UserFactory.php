<?php

namespace Database\Factories;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $createdAt = Carbon::now()
            ->subYears(rand(0, 4))
            ->subMonths(rand(0, 11))
            ->subDays(rand(0, 30))
            ->subHours(rand(0, 23))
            ->subMinutes(rand(0, 59));

        $updatedBy = $this->faker->boolean(50);

        return [
            'username' => $this->faker->unique()->userName,
            'slug' => Str::slug($this->faker->unique()->userName . '-' . Str::random(5)),
            'email' => $this->faker->unique()->safeEmail,
            'mobile_number' => '01' . $this->faker->unique()->numberBetween(000000000, 999999999),
            // 'national_id' => $this->faker->unique()->optional()->numerify('##############'),
            'national_id' => null,
            'password' => Hash::make('123456789'),
            'status' => $this->faker->randomElement(UserStatus::values()),
            'type' => $this->faker->randomElement(UserType::values()),
            'can_login' => true,
            'status_details' => null,
            'email_verified_at' => now(),
            'role_id' => null,
            'remember_token' => Str::random(10),
            'created_by' => User::inRandomOrder()->value('id'),
            'updated_by' => $updatedBy ? User::inRandomOrder()->value('id') : null,
            'created_at' => $createdAt,
            'updated_at' => $updatedBy ? $createdAt->copy()->addMinutes(rand(0, 120)) : null,
        ];
    }
}
