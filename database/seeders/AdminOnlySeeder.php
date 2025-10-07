<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminOnlySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates only one admin user.
     */
    public function run(): void
    {
        $this->command->info('🧹 Creating admin account...');
        
        $adminEmail = 'admin@onlinecourse.com';
        
        $admin = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'System Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now()
            ]
        );
        
        $this->command->info('✅ Admin account created successfully!');
        $this->command->newLine();
        $this->command->info('📋 Login Credentials:');
        $this->command->info('👨‍💼 Admin Email: ' . $adminEmail);
        $this->command->info('🔐 Admin Password: admin123');
        $this->command->newLine();
    }
}
