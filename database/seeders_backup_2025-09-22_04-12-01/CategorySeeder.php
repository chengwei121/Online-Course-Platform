<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Web Development',
                'description' => 'Learn modern web development technologies and frameworks',
                'slug' => 'web-development',
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Build mobile applications for iOS and Android',
                'slug' => 'mobile-development',
            ],
            [
                'name' => 'Data Science',
                'description' => 'Master data analysis and machine learning',
                'slug' => 'data-science',
            ],
            [
                'name' => 'Design',
                'description' => 'Learn UI/UX design and graphic design principles',
                'slug' => 'design',
            ],
            [
                'name' => 'Business',
                'description' => 'Develop business and entrepreneurship skills',
                'slug' => 'business',
            ],
            [
                'name' => 'Marketing',
                'description' => 'Learn digital marketing and growth strategies',
                'slug' => 'marketing',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }
    }
} 