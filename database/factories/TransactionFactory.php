<?php

namespace Database\Factories;

use App\Enums\TransactionTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value'             => $this->faker->randomFloat(2),
            'type'              => $this->faker->randomElement(TransactionTypeEnum::cases()),
            'when'              => now(),
            'description'       => $this->faker->word(),
            'category_id'       => $this->faker->randomElement([1, 2]),
            'user_id'           => User::query()->first()
        ];
    }
}
