@echo off
echo ============================================
echo XAMPP PHP Configuration Update for Video Uploads
echo ============================================
echo.

REM Find XAMPP installation
set "XAMPP_PATH=C:\xampp"
if not exist "%XAMPP_PATH%\php\php.ini" (
    set "XAMPP_PATH=C:\xampp-portable"
)
if not exist "%XAMPP_PATH%\php\php.ini" (
    echo ERROR: Cannot find XAMPP installation!
    echo Please make sure XAMPP is installed in C:\xampp or C:\xampp-portable
    pause
    exit /b 1
)

echo Found XAMPP at: %XAMPP_PATH%
echo.

REM Backup original php.ini
echo Creating backup of php.ini...
copy "%XAMPP_PATH%\php\php.ini" "%XAMPP_PATH%\php\php.ini.backup_%DATE:~10,4%%DATE:~4,2%%DATE:~7,2%" >nul
echo Backup created successfully!
echo.

REM Create temporary update script
echo Creating updated php.ini...
powershell -Command ^
"$content = Get-Content '%XAMPP_PATH%\php\php.ini'; " ^
"$content = $content -replace 'upload_max_filesize = .*', 'upload_max_filesize = 500M'; " ^
"$content = $content -replace 'post_max_size = .*', 'post_max_size = 500M'; " ^
"$content = $content -replace 'max_execution_time = .*', 'max_execution_time = 300'; " ^
"$content = $content -replace 'max_input_time = .*', 'max_input_time = 300'; " ^
"$content = $content -replace 'memory_limit = .*', 'memory_limit = 512M'; " ^
"$content | Set-Content '%XAMPP_PATH%\php\php.ini'"

echo PHP configuration updated!
echo.

echo ============================================
echo IMPORTANT: You need to restart Apache now!
echo ============================================
echo.
echo Please:
echo 1. Open XAMPP Control Panel
echo 2. Stop Apache
echo 3. Start Apache
echo 4. Restart your Laravel server: php artisan serve
echo.
echo Then try uploading your video again.
echo.

pause