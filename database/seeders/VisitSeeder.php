<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visit;

class VisitSeeder extends Seeder
{
    public function run(): void
    {
        Visit::factory(30)->create();
    }
}
