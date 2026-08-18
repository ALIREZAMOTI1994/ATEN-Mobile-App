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

        User::factory()->create([
            'name' => 'ATEN Admin',
            'email' => 'admin@atenlink.com',
            'role' => 'admin',
        ]);
    }
}
