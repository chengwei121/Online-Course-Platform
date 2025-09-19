<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->bind('path.public', fn() => __DIR__ . '/public');

use App\Models\Course;

echo "Checking course thumbnails:\n";

$courses = Course::select('id', 'title', 'thumbnail')->take(5)->get();

foreach($courses as $course) {
    echo "Course: {$course->title}\n";
    echo "Thumbnail: " . ($course->thumbnail ?: 'NULL') . "\n";
    
    if ($course->thumbnail) {
        $imagePath = public_path('storage/' . $course->thumbnail);
        echo "Full path: {$imagePath}\n";
        echo "File exists: " . (file_exists($imagePath) ? 'YES' : 'NO') . "\n";
    }
    echo "---\n";
}

// Check storage link
$storageLink = public_path('storage');
echo "\nStorage link info:\n";
echo "Storage symlink path: {$storageLink}\n";
echo "Symlink exists: " . (file_exists($storageLink) ? 'YES' : 'NO') . "\n";
echo "Is link: " . (is_link($storageLink) ? 'YES' : 'NO') . "\n";

if (is_link($storageLink)) {
    echo "Points to: " . readlink($storageLink) . "\n";
}

// Check if we have any image files in storage
$storageAppPublic = storage_path('app/public');
echo "\nStorage app/public directory: {$storageAppPublic}\n";
echo "Directory exists: " . (is_dir($storageAppPublic) ? 'YES' : 'NO') . "\n";

if (is_dir($storageAppPublic)) {
    $files = glob($storageAppPublic . '/*');
    echo "Files in storage/app/public: " . count($files) . "\n";
    foreach($files as $file) {
        if (is_file($file)) {
            echo "  - " . basename($file) . "\n";
        }
    }
}