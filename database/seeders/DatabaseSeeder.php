<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * php artisan migrate:fresh --seed (to refresh the database and run the seeder)
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Pork',
            'email' => 'test@example.com',
        ]);

        Mission::factory(15)->create();
    }
}
