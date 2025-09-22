<?php
// Check PHP upload configuration
echo "<h2>PHP Upload Configuration Status</h2>";
echo "<table border='1' style='border-collapse: collapse; padding: 10px;'>";
echo "<tr><th>Setting</th><th>Current Value</th><th>Recommended</th><th>Status</th></tr>";

$settings = [
    'upload_max_filesize' => ['current' => ini_get('upload_max_filesize'), 'recommended' => '500M'],
    'post_max_size' => ['current' => ini_get('post_max_size'), 'recommended' => '500M'],
    'max_execution_time' => ['current' => ini_get('max_execution_time'), 'recommended' => '300'],
    'max_input_time' => ['current' => ini_get('max_input_time'), 'recommended' => '300'],
    'memory_limit' => ['current' => ini_get('memory_limit'), 'recommended' => '512M'],
    'max_file_uploads' => ['current' => ini_get('max_file_uploads'), 'recommended' => '20'],
];

foreach ($settings as $setting => $values) {
    $status = ($values['current'] >= $values['recommended']) ? 
        "<span style='color: green;'>✓ OK</span>" : 
        "<span style='color: red;'>✗ NEEDS UPDATE</span>";
    
    echo "<tr>";
    echo "<td><strong>{$setting}</strong></td>";
    echo "<td>{$values['current']}</td>";
    echo "<td>{$values['recommended']}</td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Quick Fix Instructions:</h3>";
echo "<ol>";
echo "<li>Edit <code>C:\\xampp\\php\\php.ini</code></li>";
echo "<li>Update the settings marked with ✗</li>";
echo "<li>Restart Apache in XAMPP Control Panel</li>";
echo "<li>Restart your Laravel server: <code>php artisan serve</code></li>";
echo "<li>Refresh this page to verify changes</li>";
echo "</ol>";

echo "<h3>Alternative: Use smaller video files (under 10MB) until configuration is updated.</h3>";
?>