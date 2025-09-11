<?php declare(strict_types = 1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name'  => 'Admin User',
            'email' => 'admin@example.com',
            'role'  => UserRole::ADMIN,
        ]);

        User::factory()->create([
            'name'  => 'Felipe Rodrigues',
            'email' => 'fehzin_@outlook.com',
            'role'  => UserRole::ADMIN,
        ]);

        // $this->call(CategorySeeder::class);
        // $this->call(TransactionSeeder::class);
    }
}
