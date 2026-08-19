<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            IndustrySeeder::class,
            ProductSeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'admin@atenlink.com'],
            User::factory()->raw(['email' => 'admin@atenlink.com', 'name' => 'ATEN Admin', 'role' => 'admin'])
        );
    }
}
