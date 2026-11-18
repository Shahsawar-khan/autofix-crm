<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default Test User (Optional)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Ye line humare AutoFix Workshop ka data insert karegi
        $this->call([
            LeadSeeder::class,
        ]);
    }
}