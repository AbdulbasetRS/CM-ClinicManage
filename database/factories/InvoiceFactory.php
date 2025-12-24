<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $createdAt = Carbon::now()
            ->subYears(rand(0, 4))
            ->subMonths(rand(0, 11))
            ->subDays(rand(0, 30))
            ->subHours(rand(0, 23))
            ->subMinutes(rand(0, 59));

        $patient = User::where('type', 'patient')->inRandomOrder()->first();
        $visit = Visit::inRandomOrder()->first();

        $total = $this->faker->randomFloat(2, 50, 500);
        $discount = $this->faker->randomFloat(2, 0, 50);
        $final = $total - $discount;

        return [
            'patient_id' => $patient?->id ?? User::factory()->patient()->create()->id,
            'visit_id' => $visit?->id,
            'total_amount' => $total,
            'discount' => $discount,
            'final_amount' => $final,
            'status' => $this->faker->randomElement(InvoiceStatus::values()),
            'payment_method' => $this->faker->randomElement(PaymentMethod::values()),
            'invoice_date' => $createdAt,
            'created_by' => User::inRandomOrder()->value('id'),
            'updated_by' => $this->faker->boolean(50) ? User::inRandomOrder()->value('id') : null,
        ];
    }
}
