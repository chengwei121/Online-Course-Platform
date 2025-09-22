<?php
/**
 * XAMPP PHP Configuration Script for Video Uploads
 * This script modifies the XAMPP php.ini file to allow large video uploads
 */

echo "XAMPP Video Upload Configuration Script\n";
echo "=====================================\n\n";

// Get PHP ini file location
$iniFile = php_ini_loaded_file();
echo "PHP ini file: $iniFile\n";

if (!$iniFile || !file_exists($iniFile)) {
    die("Error: Could not locate php.ini file\n");
}

// Create backup
$backupFile = $iniFile . '.backup.' . date('Y-m-d_H-i-s');
echo "Creating backup: $backupFile\n";

if (!copy($iniFile, $backupFile)) {
    die("Error: Could not create backup of php.ini\n");
}

// Read current php.ini content
$iniContent = file_get_contents($iniFile);
if ($iniContent === false) {
    die("Error: Could not read php.ini file\n");
}

// Settings to modify
$settings = [
    'upload_max_filesize' => '500M',
    'post_max_size' => '500M',
    'max_execution_time' => '300',
    'memory_limit' => '1024M',
    'max_input_time' => '300'
];

echo "\nModifying PHP settings...\n";

foreach ($settings as $setting => $value) {
    $pattern = "/^(\s*;?\s*)$setting\s*=\s*.*/m";
    $replacement = "$setting = $value";
    
    if (preg_match($pattern, $iniContent)) {
        $iniContent = preg_replace($pattern, $replacement, $iniContent);
        echo "✓ Updated $setting = $value\n";
    } else {
        // If setting doesn't exist, add it to the end
        $iniContent .= "\n; Added by video upload configuration script\n";
        $iniContent .= "$setting = $value\n";
        echo "✓ Added $setting = $value\n";
    }
}

// Write modified content back to php.ini
if (file_put_contents($iniFile, $iniContent) === false) {
    die("Error: Could not write to php.ini file. Make sure you're running as administrator.\n");
}

echo "\n✓ PHP configuration updated successfully!\n";
echo "\nNext steps:\n";
echo "1. Restart Apache in XAMPP Control Panel\n";
echo "2. Test the video upload functionality\n";
echo "\nIf you need to restore the original settings, use the backup file:\n";
echo "$backupFile\n";
?>