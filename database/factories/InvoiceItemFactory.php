<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        $invoice = Invoice::inRandomOrder()->first();

        $amount = $this->faker->randomFloat(2, 10, 100);
        $quantity = $this->faker->numberBetween(1, 5);

        return [
            'invoice_id' => $invoice?->id ?? Invoice::factory()->create()->id,
            'description' => $this->faker->sentence(3),
            'amount' => $amount,
            'quantity' => $quantity,
            'created_by' => User::inRandomOrder()->value('id'),
            'updated_by' => $this->faker->boolean(50) ? User::inRandomOrder()->value('id') : null,
        ];
    }
}
