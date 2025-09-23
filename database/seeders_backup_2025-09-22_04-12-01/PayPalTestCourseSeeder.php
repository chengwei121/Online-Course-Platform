<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Category;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PayPalTestCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Get or create categories
            $categories = [
                'Programming' => 'Learn various programming languages and frameworks',
                'Design' => 'Master the art of digital design and user experience',
                'Data Science' => 'Dive deep into data analysis and machine learning',
                'Business' => 'Develop business and entrepreneurship skills'
            ];

            $categoryModels = [];
            foreach ($categories as $name => $description) {
                $categoryModels[$name] = Category::firstOrCreate(
                    ['name' => $name],
                    [
                        'description' => $description,
                        'slug' => Str::slug($name)
                    ]
                );
            }

            // Get teachers
            $teachers = Teacher::all();
            if ($teachers->isEmpty()) {
                $this->command->error('No teachers found. Please run AdminAndTeacherSeeder first.');
                return;
            }

            // Create test courses with different price points
            $courses = [
                [
                    'title' => 'Complete Web Development Bootcamp',
                    'description' => 'Learn HTML, CSS, JavaScript, React, Node.js, and MongoDB. Build 10+ projects and become a full-stack developer.',
                    'price' => 99.99,
                    'is_free' => false,
                    'category' => 'Programming',
                    'level' => 'beginner',
                    'duration' => '12 weeks',
                    'learning_hours' => 80,
                    'skills_to_learn' => ['HTML5', 'CSS3', 'JavaScript', 'React', 'Node.js', 'MongoDB', 'Express.js'],
                    'status' => 'published'
                ],
                [
                    'title' => 'Python for Data Science',
                    'description' => 'Master Python programming for data analysis, visualization, and machine learning. Work with real datasets.',
                    'price' => 79.99,
                    'is_free' => false,
                    'category' => 'Data Science',
                    'level' => 'intermediate',
                    'duration' => '8 weeks',
                    'learning_hours' => 60,
                    'skills_to_learn' => ['Python', 'Pandas', 'NumPy', 'Matplotlib', 'Scikit-learn', 'Jupyter'],
                    'status' => 'published'
                ],
                [
                    'title' => 'UX/UI Design Masterclass',
                    'description' => 'Learn user experience and interface design from scratch. Master Figma, design principles, and user research.',
                    'price' => 129.99,
                    'is_free' => false,
                    'category' => 'Design',
                    'level' => 'beginner',
                    'duration' => '10 weeks',
                    'learning_hours' => 70,
                    'skills_to_learn' => ['Figma', 'User Research', 'Wireframing', 'Prototyping', 'Design Systems'],
                    'status' => 'published'
                ],
                [
                    'title' => 'Introduction to Programming',
                    'description' => 'Start your coding journey with this free introductory course. Learn basic programming concepts.',
                    'price' => 0,
                    'is_free' => true,
                    'category' => 'Programming',
                    'level' => 'beginner',
                    'duration' => '4 weeks',
                    'learning_hours' => 20,
                    'skills_to_learn' => ['Programming Basics', 'Logic', 'Problem Solving'],
                    'status' => 'published'
                ],
                [
                    'title' => 'Digital Marketing Strategy',
                    'description' => 'Learn modern digital marketing techniques, SEO, social media marketing, and analytics.',
                    'price' => 59.99,
                    'is_free' => false,
                    'category' => 'Business',
                    'level' => 'intermediate',
                    'duration' => '6 weeks',
                    'learning_hours' => 40,
                    'skills_to_learn' => ['SEO', 'Social Media', 'Google Analytics', 'Content Marketing'],
                    'status' => 'published'
                ]
            ];

            foreach ($courses as $courseData) {
                // Get random teacher
                $teacher = $teachers->random();
                $category = $categoryModels[$courseData['category']];
                
                // Remove category from course data
                unset($courseData['category']);
                
                Course::firstOrCreate(
                    ['title' => $courseData['title']],
                    array_merge($courseData, [
                        'slug' => Str::slug($courseData['title']),
                        'instructor_id' => $teacher->id,
                        'category_id' => $category->id,
                        'average_rating' => rand(40, 50) / 10, // 4.0 to 5.0
                        'total_ratings' => rand(10, 100),
                    ])
                );

                $this->command->info('✅ Course created: ' . $courseData['title'] . ' - $' . $courseData['price']);
            }
        });
    }
}