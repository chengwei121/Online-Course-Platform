<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instructor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class InstructorSeeder extends Seeder
{
    private function downloadAndStoreImage($url, $name)
    {
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $filename = 'instructors/' . Str::slug($name) . '-' . Str::random(10) . '.' . $extension;
                
                // Ensure the instructors directory exists
                Storage::disk('public')->makeDirectory('instructors');
                
                // Store the file and log the result
                $success = Storage::disk('public')->put($filename, $response->body());
                if ($success) {
                    Log::info("Successfully stored image: " . $filename);
                    return $filename;
                } else {
                    Log::error("Failed to store image: " . $filename);
                }
            }
        } catch (\Exception $e) {
            Log::error("Error downloading image for {$name}: " . $e->getMessage());
            return null;
        }
        return null;
    }

    public function run(): void
    {
        $instructors = [
            [
                'name' => 'Dr. Sarah Chen',
                'email' => 'sarah.chen@example.com',
                'title' => 'Data Science & AI Expert',
                'bio' => 'Ph.D. in Computer Science with 10+ years of experience in Machine Learning and AI. Former lead researcher at Google AI, now dedicated to making AI education accessible to everyone.',
                'profile_picture' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=388&q=80',
                'courses_count' => 15,
                'students_count' => 25000,
            ],
            [
                'name' => 'Prof. James Martinez',
                'email' => 'james.martinez@example.com',
                'title' => 'Full Stack Development Lead',
                'bio' => 'Senior Software Engineer with expertise in modern web technologies. 12+ years of industry experience at top tech companies. Known for his practical, project-based teaching approach.',
                'profile_picture' => 'https://images.unsplash.com/photo-1556157382-97eda2d62296?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80',
                'courses_count' => 12,
                'students_count' => 18000,
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@example.com',
                'title' => 'UX/UI Design Specialist',
                'bio' => 'Award-winning designer with a passion for creating beautiful and functional user experiences. Former Design Lead at Adobe, now helping students master the art of modern design.',
                'profile_picture' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=461&q=80',
                'courses_count' => 8,
                'students_count' => 15000,
            ],
        ];

        foreach ($instructors as $instructor) {
            // Download and store the image
            $localProfilePicture = $this->downloadAndStoreImage(
                $instructor['profile_picture'],
                $instructor['name']
            );

            // Log the profile picture path
            Log::info("Profile picture path for {$instructor['name']}: " . $localProfilePicture);

            $instructorModel = Instructor::updateOrCreate(
                ['email' => $instructor['email']],
                [
                    'name' => $instructor['name'],
                    'title' => $instructor['title'],
                    'bio' => $instructor['bio'],
                    'profile_picture' => $localProfilePicture,
                    'courses_count' => $instructor['courses_count'],
                    'students_count' => $instructor['students_count'],
                ]
            );

            // Verify the saved data
            Log::info("Saved instructor data: " . json_encode($instructorModel->toArray()));
        }
    }
} 