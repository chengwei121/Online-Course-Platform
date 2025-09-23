<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminAndTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Create Admin User
            $this->createAdmin();
            
            // Create Teacher Users
            $this->createTeachers();
        });
    }

    private function createAdmin()
    {
        $adminEmail = 'admin@onlinecourse.com';
        
        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'System Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now()
            ]);
            
            $this->command->info('✅ Admin user created successfully!');
            $this->command->info('   Email: ' . $adminEmail);
            $this->command->info('   Password: admin123');
        } else {
            $this->command->info('ℹ️  Admin user already exists.');
        }
    }

    private function createTeachers()
    {
        $teachers = [
            [
                'user_data' => [
                    'name' => 'Dr. Sarah Chen',
                    'email' => 'sarah.chen@onlinecourse.com',
                    'password' => Hash::make('teacher123'),
                    'role' => 'instructor',
                    'email_verified_at' => now()
                ],
                'teacher_data' => [
                    'name' => 'Dr. Sarah Chen',
                    'email' => 'sarah.chen@onlinecourse.com',
                    'phone' => '+1-555-0101',
                    'qualification' => 'Ph.D. in Computer Science',
                    'bio' => 'Ph.D. in Computer Science with 10+ years of experience in Machine Learning and AI. Former lead researcher at Google AI, now dedicated to making AI education accessible to everyone.',
                    'department' => 'Computer Science',
                    'date_of_birth' => '1985-05-15',
                    'address' => '123 Tech Street, Silicon Valley, CA 94105',
                    'hourly_rate' => 150.00,
                    'status' => 'active'
                ]
            ],
            [
                'user_data' => [
                    'name' => 'Prof. James Martinez',
                    'email' => 'james.martinez@onlinecourse.com',
                    'password' => Hash::make('teacher123'),
                    'role' => 'instructor',
                    'email_verified_at' => now()
                ],
                'teacher_data' => [
                    'name' => 'Prof. James Martinez',
                    'email' => 'james.martinez@onlinecourse.com',
                    'phone' => '+1-555-0102',
                    'qualification' => 'M.S. in Software Engineering',
                    'bio' => 'Senior Software Engineer with expertise in modern web technologies. 12+ years of industry experience at top tech companies. Known for his practical, project-based teaching approach.',
                    'department' => 'Software Engineering',
                    'date_of_birth' => '1982-08-22',
                    'address' => '456 Developer Ave, Austin, TX 78701',
                    'hourly_rate' => 125.00,
                    'status' => 'active'
                ]
            ],
            [
                'user_data' => [
                    'name' => 'Emily Rodriguez',
                    'email' => 'emily.rodriguez@onlinecourse.com',
                    'password' => Hash::make('teacher123'),
                    'role' => 'instructor',
                    'email_verified_at' => now()
                ],
                'teacher_data' => [
                    'name' => 'Emily Rodriguez',
                    'email' => 'emily.rodriguez@onlinecourse.com',
                    'phone' => '+1-555-0103',
                    'qualification' => 'B.F.A. in Graphic Design, UX Certification',
                    'bio' => 'Award-winning designer with a passion for creating beautiful and functional user experiences. Former Design Lead at Adobe, now helping students master the art of modern design.',
                    'department' => 'Design',
                    'date_of_birth' => '1988-12-10',
                    'address' => '789 Creative Blvd, New York, NY 10001',
                    'hourly_rate' => 100.00,
                    'status' => 'active'
                ]
            ]
        ];

        foreach ($teachers as $teacherInfo) {
            // Check if user already exists
            if (!User::where('email', $teacherInfo['user_data']['email'])->exists()) {
                // Create user
                $user = User::create($teacherInfo['user_data']);
                
                // Create teacher profile
                $teacherData = $teacherInfo['teacher_data'];
                $teacherData['user_id'] = $user->id;
                
                Teacher::create($teacherData);
                
                $this->command->info('✅ Teacher created: ' . $teacherInfo['user_data']['name']);
                $this->command->info('   Email: ' . $teacherInfo['user_data']['email']);
                $this->command->info('   Password: teacher123');
            } else {
                $this->command->info('ℹ️  Teacher already exists: ' . $teacherInfo['user_data']['name']);
            }
        }
    }
}