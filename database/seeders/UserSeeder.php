<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    // $types = ['user', 'admin', 'it', 'teacher', 'tester', 'employee', 'student', 'guardian'];

    public function run(): void
    {
        $admin = User::firstOrCreate([
            'id' => 1,
        ], [
            'id' => 1,
            'username' => 'abdulbaset_rs',
            'slug' => Str::slug('abdulbaset_rs'),
            'email' => 'abdulbaset_rs@digitalatum.com',
            'mobile_number' => '01097579845',
            'password' => Hash::make('123456789'),
            'status' => 'active',
            'type' => 'admin',
            'can_login' => true,
            'email_verified_at' => now(),
        ]);
        $admin->profile()->firstOrCreate([
            'user_id' => 1,
        ], [
            'first_name' => 'عبدالباسط',
            'middle_name' => 'رضا',
            'last_name' => 'سيد',
            'gender' => 'male',
            'title' => 'مبرمج Laravel',
            'date_of_birth' => '1995-01-01',
            'whatapp_number' => '01097579845',
            'telegram_number' => '01097579845',
            'address' => 'الجيزة - إمبابة',
            'note' => 'صاحب المشروع',
            'avatar' => 'https://ui-avatars.com/api/?name=Abdulbaset+Sayed&size=512&background=random',
        ]);
        $admin->authProviders()->firstOrCreate(
            [
                'provider_name' => 'google',
                'provider_user_id' => '1234567890',
                'email' => 'abdulbaset_rs@digitalatum.com',
                'name' => 'Abdulbaset R. Sayed',
                'avatar' => 'https://ui-avatars.com/api/?name=Abdulbaset+Sayed&size=512&background=random',
            ]
        );
        $user = User::firstOrCreate(
            [
                'id' => 2,
            ],
            [
                'id' => 2,
                'username' => 'user',
                'slug' => 'user',
                'email' => 'user@example.com',
                'mobile_number' => '01'.rand(100000000, 999999999),
                'password' => Hash::make('123456789'),
                'status' => 'active',
                'type' => 'user',
                'can_login' => true,
                'email_verified_at' => now(),
            ]
        );
        $user->profile()->firstOrCreate(Profile::factory()->raw());

        User::factory()
            ->count(20)
            ->create()
            ->each(function ($user) {
                $user->profile()->firstOrCreate(Profile::factory()->raw());
            });
    }
}
