<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Category;
use App\Models\Teacher;
use App\Models\CourseReview;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Define category images
        $categoryImages = [
            'Web Development' => [
                'images/courses/web/javascript-course.jpg',
                'images/courses/web/laravel-vue-course.jpg',
                'images/courses/web/responsive-design-course.jpg',
            ],
            'Mobile Development' => [
                'images/courses/mobile/ios-swift-course.jpg',
                'images/courses/mobile/android-kotlin-course.jpg',
                'images/courses/mobile/react-native-course.jpg',
            ],
            'Data Science' => [
                'images/courses/data/python-data-science.jpg',
                'images/courses/data/machine-learning.jpg',
                'images/courses/data/deep-learning.jpg',
            ],
            'Design' => [
                'images/courses/design/uiux-design.jpg',
                'images/courses/design/photoshop-course.jpg',
                'images/courses/design/after-effects.jpg',
            ],
            'Business' => [
                'images/courses/business/entrepreneurship.jpg',
                'images/courses/business/project-management.jpg',
                'images/courses/business/financial-analysis.jpg',
            ],
            'Marketing' => [
                'images/courses/marketing/digital-marketing.jpg',
                'images/courses/marketing/social-media.jpg',
                'images/courses/marketing/seo-course.jpg',
            ],
        ];

        // Create categories
        foreach ($categoryImages as $categoryName => $images) {
            Category::firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                [
                    'name' => $categoryName,
                    'description' => "Learn {$categoryName} from expert instructors",
                ]
            );
        }

        // Get the teacher from the teachers table (since courses reference teachers.id)
        $teacher = \App\Models\Teacher::where('email', 'teacher@onlinecourse.com')->first();
        
        // Use the same teacher for all courses since we only have one
        $webDevInstructor = $teacher;
        $dataInstructor = $teacher;
        $designInstructor = $teacher;

        // Web Development courses (taught by James Martinez)
        $this->createWebDevelopmentCourses($webDevInstructor, $categoryImages['Web Development']);

        // Mobile Development courses (also taught by James Martinez as it's related to development)
        $this->createMobileDevelopmentCourses($webDevInstructor, $categoryImages['Mobile Development']);

        // Data Science courses (taught by Sarah Chen)
        $this->createDataScienceCourses($dataInstructor, $categoryImages['Data Science']);

        // Design courses (taught by Emily Rodriguez)
        $this->createDesignCourses($designInstructor, $categoryImages['Design']);

        // Business courses (taught by Sarah Chen - she has business analytics background)
        $this->createBusinessCourses($dataInstructor, $categoryImages['Business']);

        // Marketing courses (taught by Emily Rodriguez - she has marketing design experience)
        $this->createMarketingCourses($designInstructor, $categoryImages['Marketing']);
    }

    private function createWebDevelopmentCourses($instructor, $images)
    {
        $category = Category::where('name', 'Web Development')->first();
        
        $courses = [
            [
                'title' => 'Modern JavaScript Complete Guide',
                'description' => 'Master JavaScript from basics to advanced concepts including ES6+ features.',
                'price' => 49.99,
                'duration' => 20,
                'level' => 'intermediate',
                'thumbnail' => 'images/courses/web/javascript-course.jpg',
                'skills_to_learn' => ['JavaScript', 'ES6+', 'DOM Manipulation', 'Async Programming'],
            ],
            [
                'title' => 'Full Stack Web Development with Laravel & Vue.js',
                'description' => 'Build modern web applications with Laravel backend and Vue.js frontend.',
                'price' => 79.99,
                'duration' => 30,
                'level' => 'advanced',
                'thumbnail' => 'images/courses/web/laravel-vue-course.jpg',
                'skills_to_learn' => ['Laravel', 'Vue.js', 'RESTful APIs', 'Authentication'],
            ],
            [
                'title' => 'Responsive Web Design Masterclass',
                'description' => 'Create beautiful, responsive websites using HTML5, CSS3, and modern design principles.',
                'price' => 0,
                'duration' => 15,
                'level' => 'beginner',
                'thumbnail' => 'images/courses/web/responsive-design-course.jpg',
                'skills_to_learn' => ['HTML5', 'CSS3', 'Flexbox', 'CSS Grid'],
                'is_free' => true,
            ],
        ];

        foreach ($courses as $courseData) {
            $this->createCourse($instructor, $category, $courseData, $images[array_rand($images)]);
        }
    }

    private function createMobileDevelopmentCourses($instructor, $images)
    {
        $category = Category::where('name', 'Mobile Development')->first();
        
        $courses = [
            [
                'title' => 'iOS App Development with Swift',
                'description' => 'Learn to build iOS applications using Swift and Xcode.',
                'price' => 89.99,
                'duration' => 25,
                'level' => 'intermediate',
                'thumbnail' => 'images/courses/mobile/ios-swift-course.jpg',
                'skills_to_learn' => ['Swift', 'iOS Development', 'UIKit', 'Core Data'],
            ],
            [
                'title' => 'Android Development with Kotlin',
                'description' => 'Create Android apps using Kotlin and Android Studio.',
                'price' => 79.99,
                'duration' => 28,
                'level' => 'intermediate',
                'thumbnail' => 'images/courses/mobile/android-kotlin-course.jpg',
                'skills_to_learn' => ['Kotlin', 'Android SDK', 'Material Design', 'SQLite'],
            ],
            [
                'title' => 'Cross-Platform Development with React Native',
                'description' => 'Build mobile apps for both iOS and Android using React Native.',
                'price' => 69.99,
                'duration' => 22,
                'level' => 'advanced',
                'thumbnail' => 'images/courses/mobile/react-native-course.jpg',
                'skills_to_learn' => ['React Native', 'JavaScript', 'Mobile UI', 'APIs'],
            ],
        ];

        foreach ($courses as $courseData) {
            $this->createCourse($instructor, $category, $courseData, $images[array_rand($images)]);
        }
    }

    private function createDataScienceCourses($instructor, $images)
    {
        $category = Category::where('name', 'Data Science')->first();
        
        $courses = [
            [
                'title' => 'Python for Data Science',
                'description' => 'Learn Python programming for data analysis and visualization.',
                'price' => 0,
                'duration' => 18,
                'level' => 'beginner',
                'thumbnail' => 'images/courses/data/python-data-science.jpg',
                'skills_to_learn' => ['Python', 'Pandas', 'NumPy', 'Matplotlib'],
                'is_free' => true,
            ],
            [
                'title' => 'Machine Learning Fundamentals',
                'description' => 'Introduction to machine learning algorithms and applications.',
                'price' => 89.99,
                'duration' => 25,
                'level' => 'intermediate',
                'thumbnail' => 'images/courses/data/machine-learning.jpg',
                'skills_to_learn' => ['Machine Learning', 'Scikit-learn', 'Statistical Analysis', 'Model Training'],
            ],
            [
                'title' => 'Deep Learning with TensorFlow',
                'description' => 'Master deep learning concepts using TensorFlow framework.',
                'price' => 99.99,
                'duration' => 30,
                'level' => 'advanced',
                'thumbnail' => 'images/courses/data/deep-learning.jpg',
                'skills_to_learn' => ['Deep Learning', 'TensorFlow', 'Neural Networks', 'Computer Vision'],
            ],
        ];

        foreach ($courses as $courseData) {
            $this->createCourse($instructor, $category, $courseData, $images[array_rand($images)]);
        }
    }

    private function createDesignCourses($instructor, $images)
    {
        $category = Category::where('name', 'Design')->first();
        
        $courses = [
            [
                'title' => 'UI/UX Design Principles',
                'description' => 'Learn the fundamentals of user interface and user experience design.',
                'price' => 0,
                'duration' => 15,
                'level' => 'beginner',
                'thumbnail' => 'images/courses/design/uiux-design.jpg',
                'skills_to_learn' => ['UI Design', 'UX Design', 'Wireframing', 'Prototyping'],
                'is_free' => true,
            ],
            [
                'title' => 'Adobe Photoshop Masterclass',
                'description' => 'Master digital image editing and manipulation with Photoshop.',
                'price' => 59.99,
                'duration' => 20,
                'level' => 'intermediate',
                'thumbnail' => 'images/courses/design/photoshop-course.jpg',
                'skills_to_learn' => ['Photoshop', 'Image Editing', 'Digital Art', 'Photo Manipulation'],
            ],
            [
                'title' => 'Motion Graphics with After Effects',
                'description' => 'Create stunning motion graphics and visual effects.',
                'price' => 69.99,
                'duration' => 22,
                'level' => 'advanced',
                'thumbnail' => 'images/courses/design/after-effects.jpg',
                'skills_to_learn' => ['After Effects', 'Motion Graphics', 'Animation', 'Visual Effects'],
            ],
        ];

        foreach ($courses as $courseData) {
            $this->createCourse($instructor, $category, $courseData, $images[array_rand($images)]);
        }
    }

    private function createBusinessCourses($instructor, $images)
    {
        $category = Category::where('name', 'Business')->first();
        
        $courses = [
            [
                'title' => 'Entrepreneurship Fundamentals',
                'description' => 'Learn the basics of starting and running a successful business.',
                'price' => 0,
                'duration' => 15,
                'level' => 'beginner',
                'thumbnail' => 'images/courses/business/entrepreneurship.jpg',
                'skills_to_learn' => ['Business Planning', 'Market Analysis', 'Financial Management', 'Leadership'],
                'is_free' => true,
            ],
            [
                'title' => 'Project Management Professional',
                'description' => 'Master project management methodologies and best practices.',
                'price' => 79.99,
                'duration' => 25,
                'level' => 'intermediate',
                'thumbnail' => 'images/courses/business/project-management.jpg',
                'skills_to_learn' => ['Project Management', 'Agile', 'Scrum', 'Risk Management'],
            ],
            [
                'title' => 'Financial Analysis and Modeling',
                'description' => 'Learn financial modeling and analysis techniques.',
                'price' => 89.99,
                'duration' => 28,
                'level' => 'advanced',
                'thumbnail' => 'images/courses/business/financial-analysis.jpg',
                'skills_to_learn' => ['Financial Modeling', 'Excel', 'Valuation', 'Financial Analysis'],
            ],
        ];

        foreach ($courses as $courseData) {
            $this->createCourse($instructor, $category, $courseData, $images[array_rand($images)]);
        }
    }

    private function createMarketingCourses($instructor, $images)
    {
        $category = Category::where('name', 'Marketing')->first();
        
        $courses = [
            [
                'title' => 'Digital Marketing Strategy',
                'description' => 'Develop comprehensive digital marketing strategies.',
                'price' => 0,
                'duration' => 18,
                'level' => 'beginner',
                'thumbnail' => 'images/courses/marketing/digital-marketing.jpg',
                'skills_to_learn' => ['Digital Marketing', 'Content Strategy', 'SEO', 'Social Media'],
                'is_free' => true,
            ],
            [
                'title' => 'Social Media Marketing',
                'description' => 'Master social media marketing across multiple platforms.',
                'price' => 49.99,
                'duration' => 15,
                'level' => 'intermediate',
                'thumbnail' => 'images/courses/marketing/social-media.jpg',
                'skills_to_learn' => ['Social Media', 'Content Creation', 'Analytics', 'Advertising'],
            ],
            [
                'title' => 'Advanced SEO Techniques',
                'description' => 'Learn advanced search engine optimization strategies.',
                'price' => 69.99,
                'duration' => 20,
                'level' => 'advanced',
                'thumbnail' => 'images/courses/marketing/seo-course.jpg',
                'skills_to_learn' => ['SEO', 'Keyword Research', 'Link Building', 'Technical SEO'],
            ],
        ];

        foreach ($courses as $courseData) {
            $this->createCourse($instructor, $category, $courseData, $images[array_rand($images)]);
        }
    }

    private function createCourse($instructor, $category, $courseData, $image)
    {
        $course = Course::create([
            'title' => $courseData['title'],
            'description' => $courseData['description'],
            'price' => $courseData['price'],
            'thumbnail' => $image,
            'slug' => Str::slug($courseData['title']),
            'teacher_id' => $instructor->id,
            'category_id' => $category->id,
            'status' => 'published',
            'duration' => $courseData['duration'],
            'learning_hours' => rand(10, 30),
            'level' => $courseData['level'],
            'skills_to_learn' => $courseData['skills_to_learn'],
            'is_free' => $courseData['is_free'] ?? false,
        ]);

        // Get users who might be interested in this category
        $potentialStudents = User::where('role', 'student')
            ->inRandomOrder()
            ->take(rand(20, 50))
            ->get();

        $now = now();
        $reviewCount = 0;
        $totalRating = 0;

        foreach ($potentialStudents as $student) {
            // Create enrollment with a date in the past (1-180 days ago)
            $enrollmentDate = $now->copy()->subDays(rand(1, 180));
            
            // Create enrollment using firstOrCreate to prevent duplicates
            $enrollment = Enrollment::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ],
                [
                    'payment_status' => 'completed',
                    'amount_paid' => $courseData['price'],
                    'enrolled_at' => $enrollmentDate,
                    'completed_at' => rand(0, 1) ? $enrollmentDate->copy()->addDays(rand(7, 60)) : null
                ]
            );

            // Only create review if this is a new enrollment
            if ($enrollment->wasRecentlyCreated) {
                // Only students who completed the course or made significant progress leave reviews
                if ($enrollment->completed_at || rand(1, 100) <= 40) { // 40% chance for non-completers to review
                    // Calculate days between enrollment and review
                    $minReviewDelay = $enrollment->completed_at ? 1 : 7; // Completed students can review sooner
                    $maxReviewDelay = $enrollment->completed_at ? 30 : 90; // Completed students usually review faster
                    
                    $reviewDate = $enrollmentDate->copy()->addDays(rand($minReviewDelay, $maxReviewDelay));
                    
                    // Skip if review date would be in the future
                    if ($reviewDate->isFuture()) {
                        continue;
                    }

                    // Generate rating based on completion and course match
                    $baseRating = $enrollment->completed_at ? rand(4, 5) : rand(3, 5);
                    $rating = min(5, max(1, $baseRating + rand(-1, 1)));

                    // Create the review
                    $review = CourseReview::create([
                        'course_id' => $course->id,
                        'user_id' => $student->id,
                        'rating' => $rating,
                        'comment' => $this->getRealisticReview($rating, $enrollment->completed_at, $courseData),
                        'created_at' => $reviewDate
                    ]);

                    $reviewCount++;
                    $totalRating += $rating;
                }
            }
        }

        // Update course rating statistics
        if ($reviewCount > 0) {
            $course->update([
                'average_rating' => round($totalRating / $reviewCount, 2),
                'total_ratings' => $reviewCount
            ]);
        }
    }

    private function getRealisticReview($rating, $completed, $courseData)
    {
        $comments = [
            5 => [
                'completed' => [
                    "Absolutely fantastic course! The instructor explains {topic} concepts clearly and thoroughly. The hands-on projects really helped cement my understanding.",
                    "This course exceeded my expectations! The content is up-to-date and relevant. I especially enjoyed the sections on {specific_topic}.",
                    "One of the best online courses I've taken. The progression from basic to advanced concepts was well thought out."
                ],
                'in_progress' => [
                    "Really enjoying this course so far! The content is well-structured and the teaching style is engaging.",
                    "Great course! I'm about halfway through and have already learned so much about {topic}.",
                    "The course is excellent! Even though I haven't finished yet, I've already started using some of the techniques."
                ]
            ],
            4 => [
                'completed' => [
                    "Solid course with good content. The {specific_topic} section was particularly helpful.",
                    "Good course that covers all the essentials of {topic}. The projects were practical and useful.",
                    "Very informative course. The instructor knows the subject matter well."
                ],
                'in_progress' => [
                    "Good course so far. The pace is just right and the explanations are clear.",
                    "I'm enjoying the course. The content is relevant and well-presented.",
                    "The course is meeting my expectations. Good mix of theory and practice."
                ]
            ],
            3 => [
                'completed' => [
                    "Decent course that covers the basics of {topic}. Some sections felt rushed.",
                    "The course is okay. Good introduction to {topic}, but needed more depth.",
                    "Average course. The content is there but could be more engaging."
                ],
                'in_progress' => [
                    "It's an okay course. Some concepts need more clarity.",
                    "The course is alright. The pace is a bit uneven.",
                    "Mixed feelings about this course. The basics are covered well."
                ]
            ],
            2 => [
                'completed' => [
                    "The course needs updating. Some content feels outdated.",
                    "Expected more depth. The examples are too basic.",
                    "The course structure needs improvement."
                ],
                'in_progress' => [
                    "Not impressed so far. Examples are too basic.",
                    "Having trouble following some sections.",
                    "Might not continue. Content isn't what I expected."
                ]
            ],
            1 => [
                'completed' => [
                    "Disappointed with this course. Too basic and doesn't match the description.",
                    "Not worth the time or money. Poorly explained concepts.",
                    "The course needs a complete overhaul. Outdated content."
                ],
                'in_progress' => [
                    "Very disappointed so far. Not what was advertised.",
                    "Considering dropping this course. Poor quality content.",
                    "The material is poorly organized and explained."
                ]
            ]
        ];

        // Get appropriate comment array based on rating and completion status
        $commentArray = $comments[$rating][$completed ? 'completed' : 'in_progress'];
        $comment = $commentArray[array_rand($commentArray)];

        // Replace placeholders with course-specific information
        $replacements = [
            '{topic}' => strtolower($courseData['title']),
            '{specific_topic}' => $courseData['skills_to_learn'][array_rand($courseData['skills_to_learn'])]
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $comment);
    }
} 