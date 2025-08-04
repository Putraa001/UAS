<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LegalCaseSeeder::class,
        ]);

        $this->command->info('Database seeding completed!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@bank.com / password123'); 
        $this->command->info('Manager: manager@bank.com / password123');
        $this->command->info('User 1: legal1@bank.com / password123');
        $this->command->info('User 2: legal2@bank.com / password123');
    }
}
