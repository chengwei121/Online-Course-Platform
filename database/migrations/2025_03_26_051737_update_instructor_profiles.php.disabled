<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Instructor;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $instructors = [
            [
                'name' => 'Dr. Sarah Chen',
                'email' => 'sarah.chen@example.com',
                'title' => 'Data Science & AI Expert',
                'bio' => 'Ph.D. in Computer Science with 10+ years of experience in Machine Learning and AI. Former lead researcher at Google AI, now dedicated to making AI education accessible to everyone.',
                'profile_picture' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=388&q=80',
                'courses_count' => 15,
                'students_count' => 25000
            ],
            [
                'name' => 'Prof. James Martinez',
                'email' => 'james.martinez@example.com',
                'title' => 'Full Stack Development Lead',
                'bio' => 'Senior Software Engineer with expertise in modern web technologies. 12+ years of industry experience at top tech companies. Known for his practical, project-based teaching approach.',
                'profile_picture' => 'https://images.unsplash.com/photo-1556157382-97eda2d62296?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80',
                'courses_count' => 12,
                'students_count' => 18000
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@example.com',
                'title' => 'UX/UI Design Specialist',
                'bio' => 'Award-winning designer with a passion for creating beautiful and functional user experiences. Former Design Lead at Adobe, now helping students master the art of modern design.',
                'profile_picture' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=461&q=80',
                'courses_count' => 8,
                'students_count' => 15000
            ]
        ];

        foreach ($instructors as $instructorData) {
            Instructor::updateOrCreate(
                ['email' => $instructorData['email']],
                $instructorData
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need for down migration as we're updating existing data
    }
};
