<?php
/**
 * Test Video Upload Configuration
 * This script checks if the PHP configuration is properly set for video uploads
 */

echo "<h2>PHP Video Upload Configuration Test</h2>\n";
echo "<hr>\n";

// Check current PHP settings
$settings = [
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_execution_time' => ini_get('max_execution_time'),
    'memory_limit' => ini_get('memory_limit'),
    'max_input_time' => ini_get('max_input_time')
];

echo "<h3>✅ Current PHP Settings:</h3>\n";
echo "<ul>\n";
foreach ($settings as $setting => $value) {
    echo "<li><strong>$setting:</strong> $value</li>\n";
}
echo "</ul>\n";

// Convert to bytes for comparison
function parseSize($size) {
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
    $size = preg_replace('/[^0-9\.]/', '', $size);
    if ($unit) {
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    } else {
        return round($size);
    }
}

$uploadMaxBytes = parseSize($settings['upload_max_filesize']);
$postMaxBytes = parseSize($settings['post_max_size']);

echo "<h3>📊 Upload Capacity:</h3>\n";
echo "<ul>\n";
echo "<li><strong>Upload Max:</strong> " . number_format($uploadMaxBytes / (1024*1024), 0) . " MB</li>\n";
echo "<li><strong>Post Max:</strong> " . number_format($postMaxBytes / (1024*1024), 0) . " MB</li>\n";
echo "</ul>\n";

// Status check
if ($uploadMaxBytes >= 500 * 1024 * 1024) {
    echo "<div style='color: green; font-weight: bold; padding: 10px; border: 2px solid green; margin: 10px 0;'>\n";
    echo "✅ SUCCESS: Your PHP configuration supports video uploads up to 500MB!\n";
    echo "</div>\n";
} else {
    echo "<div style='color: red; font-weight: bold; padding: 10px; border: 2px solid red; margin: 10px 0;'>\n";
    echo "❌ WARNING: Upload limit is still below 500MB. Please restart Apache.\n";
    echo "</div>\n";
}

echo "<h3>🔗 Application Links:</h3>\n";
echo "<ul>\n";
echo "<li><a href='http://127.0.0.1:8000/teacher/login' target='_blank'>Teacher Login</a></li>\n";
echo "<li><a href='http://127.0.0.1:8000/teacher/dashboard' target='_blank'>Teacher Dashboard</a></li>\n";
echo "</ul>\n";

echo "<h3>📝 Test Instructions:</h3>\n";
echo "<ol>\n";
echo "<li>Login as a teacher</li>\n";
echo "<li>Go to a course and create a new lesson</li>\n";
echo "<li>Try uploading an MP4 video file</li>\n";
echo "<li>The upload should now work for files up to 500MB</li>\n";
echo "</ol>\n";

?>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
li { margin: 5px 0; }
</style>