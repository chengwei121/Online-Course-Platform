<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $students = [
                [
                    'user_data' => [
                        'name' => 'John Doe',
                        'email' => 'student@onlinecourse.com',
                        'password' => Hash::make('student123'),
                        'role' => 'student',
                        'email_verified_at' => now()
                    ],
                    'student_data' => [
                        'name' => 'John Doe',
                        'email' => 'student@onlinecourse.com',
                        'phone' => '+1-555-0201',
                        'date_of_birth' => '1995-03-15',
                        'address' => '123 Student Lane, College Town, CA 90210'
                    ]
                ],
                [
                    'user_data' => [
                        'name' => 'Jane Smith',
                        'email' => 'jane.smith@onlinecourse.com',
                        'password' => Hash::make('student123'),
                        'role' => 'student',
                        'email_verified_at' => now()
                    ],
                    'student_data' => [
                        'name' => 'Jane Smith',
                        'email' => 'jane.smith@onlinecourse.com',
                        'phone' => '+1-555-0202',
                        'date_of_birth' => '1993-07-22',
                        'address' => '456 Learning St, Education City, NY 12345'
                    ]
                ]
            ];

            foreach ($students as $studentInfo) {
                // Check if user already exists
                if (!User::where('email', $studentInfo['user_data']['email'])->exists()) {
                    // Create user
                    $user = User::create($studentInfo['user_data']);
                    
                    // Create student profile if Student model exists and has the right structure
                    try {
                        $studentData = $studentInfo['student_data'];
                        $studentData['user_id'] = $user->id;
                        
                        Student::create($studentData);
                        
                        $this->command->info('✅ Student created: ' . $studentInfo['user_data']['name']);
                    } catch (\Exception $e) {
                        $this->command->info('✅ Student user created: ' . $studentInfo['user_data']['name'] . ' (profile creation skipped)');
                    }
                    
                    $this->command->info('   Email: ' . $studentInfo['user_data']['email']);
                    $this->command->info('   Password: student123');
                } else {
                    $this->command->info('ℹ️  Student already exists: ' . $studentInfo['user_data']['name']);
                }
            }
        });
    }
}