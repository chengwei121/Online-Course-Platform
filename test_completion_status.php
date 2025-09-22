<?php
/**
 * Debug Course Completion Status
 * Check if course completion is working correctly
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Course Completion Status Debug</h2>\n";
echo "<hr>\n";

// Check if user is logged in via session
session_start();
if (!isset($_SESSION['laravel_session'])) {
    echo "<p style='color: red;'>Please login as a student first at: <a href='http://127.0.0.1:8000/login'>http://127.0.0.1:8000/login</a></p>\n";
    exit;
}

// Get the current user ID (you'll need to update this with actual user ID)
$userId = 1; // Replace with actual logged-in user ID

echo "<h3>📊 Checking Course Completion Logic:</h3>\n";

// Simulate the completion check
echo "<div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 10px 0;'>\n";
echo "<h4>Completion Status Logic:</h4>\n";
echo "<ul>\n";
echo "<li><strong>100% progress:</strong> 🟢 Completed</li>\n";
echo "<li><strong>1-99% progress:</strong> 🟡 In Progress</li>\n";
echo "<li><strong>0% progress:</strong> ⚫ Not Started</li>\n";
echo "</ul>\n";
echo "</div>\n";

echo "<h3>🎯 Expected Results:</h3>\n";
echo "<ul>\n";
echo "<li>Your completed course should show a <strong>green 'Course Completed!' badge</strong></li>\n";
echo "<li>The progress bar should be <strong>green</strong> instead of blue</li>\n";
echo "<li>The action button should say <strong>'Review Course'</strong> with green background</li>\n";
echo "<li>Statistics should show correct count in <strong>'Completed Courses'</strong></li>\n";
echo "</ul>\n";

echo "<h3>🔗 Test Links:</h3>\n";
echo "<ul>\n";
echo "<li><a href='http://127.0.0.1:8000/student/enrollments' target='_blank'>View Your Enrollments</a></li>\n";
echo "<li><a href='http://127.0.0.1:8000/login' target='_blank'>Student Login</a></li>\n";
echo "</ul>\n";

echo "<h3>📝 Instructions:</h3>\n";
echo "<ol>\n";
echo "<li>Login as a student</li>\n";
echo "<li>Go to 'My Learning' or 'Enrollments' page</li>\n";
echo "<li>Look for courses with 100% progress</li>\n";
echo "<li>You should see the 'Course Completed!' badge</li>\n";
echo "</ol>\n";

?>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
li { margin: 5px 0; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>