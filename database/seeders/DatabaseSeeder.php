<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Brand new database with only admin account.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting fresh database seeding...');
        
        $this->call([
            // Only seed admin account
            AdminOnlySeeder::class,
        ]);
        
        $this->command->info('🎉 Database seeding completed successfully!');
    }
}
