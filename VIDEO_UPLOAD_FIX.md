# Video Upload Configuration Instructions

## The Problem
You're getting a "413 Content Too Large" error because the video file exceeds PHP's upload limits.

## Quick Solution (Recommended)

### Option 1: Update XAMPP PHP Configuration
1. **Find your XAMPP installation** (usually `C:\xampp\`)
2. **Edit the PHP configuration file** at `C:\xampp\php\php.ini`
3. **Find and update these lines:**
   ```ini
   upload_max_filesize = 500M
   post_max_size = 500M
   max_execution_time = 300
   max_input_time = 300
   memory_limit = 512M
   ```
4. **Restart Apache** in XAMPP Control Panel
5. **Restart the Laravel server:** `php artisan serve`

### Option 2: Use Web Server Configuration
1. **Edit the `.htaccess` file** in your project root (already created)
2. **Make sure these lines are present:**
   ```apache
   php_value upload_max_filesize 500M
   php_value post_max_size 500M
   php_value max_execution_time 300
   php_value max_input_time 300
   php_value memory_limit 512M
   ```

### Option 3: Use Smaller Video Files
1. **Compress your videos** to under 10MB using tools like:
   - HandBrake (free)
   - Online video compressors
   - FFmpeg
2. **Use external video hosting** like YouTube, Vimeo, or AWS S3

## Verification
Run this command to check if settings are applied:
```bash
php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
```

Expected output:
```
upload_max_filesize: 500M
post_max_size: 500M
```

## What We Already Fixed
✅ Client-side file size validation
✅ Upload progress indicators
✅ Better error handling
✅ Server-side validation improvements
✅ Runtime configuration in controller

## Current Status
- Maximum file size: Currently 10MB (needs XAMPP config update)
- Target file size: 500MB
- File formats supported: MP4, AVI, MOV, WMV, FLV
- Error handling: Comprehensive with user-friendly messages