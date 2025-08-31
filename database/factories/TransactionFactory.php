<?php declare(strict_types = 1);

namespace Database\Factories;

use App\Enums\TransactionTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
final class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value'            => $this->faker->randomFloat(2),
            'type'             => $this->faker->randomElement(TransactionTypeEnum::cases()),
            'transaction_date' => now(),
            'description'      => $this->faker->paragraph(),
            'category_id'      => $this->faker->randomElement([1, 2]),
            'user_id'          => User::query()->first(),
        ];
    }
}
