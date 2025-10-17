@echo off
echo ========================================
echo Performance Optimization Script
echo ========================================
echo.

echo Step 1: Enabling OPcache in php.ini...
echo.
echo Please manually add these lines to C:\xampp\php\php.ini:
echo.
echo [opcache]
echo zend_extension=php_opcache.dll
echo opcache.enable=1
echo opcache.enable_cli=1
echo opcache.memory_consumption=128
echo opcache.interned_strings_buffer=8
echo opcache.max_accelerated_files=10000
echo opcache.revalidate_freq=2
echo opcache.fast_shutdown=1
echo.
echo Press any key to continue after adding OPcache settings...
pause > nul

echo.
echo Step 2: Caching Laravel routes...
php artisan route:cache
if errorlevel 1 (
    echo ERROR: Route caching failed
    exit /b 1
)

echo.
echo Step 3: Caching Laravel config...
php artisan config:cache
if errorlevel 1 (
    echo ERROR: Config caching failed
    exit /b 1
)

echo.
echo Step 4: Caching Laravel views...
php artisan view:cache
if errorlevel 1 (
    echo ERROR: View caching failed
    exit /b 1
)

echo.
echo Step 5: Optimizing Composer autoloader...
composer dump-autoload --optimize
if errorlevel 1 (
    echo WARNING: Composer optimize failed
)

echo.
echo ========================================
echo Optimization Complete!
echo ========================================
echo.
echo Please restart Apache for OPcache to take effect:
echo   1. Open XAMPP Control Panel
echo   2. Stop Apache
echo   3. Start Apache
echo.
echo Expected improvements:
echo   - Page load: 2s -^> 0.3-0.5s
echo   - Route matching: 100ms -^> 10ms
echo   - Config loading: 50ms -^> 5ms
echo.
echo To clear caches after making changes:
echo   php artisan optimize:clear
echo   php artisan route:cache
echo   php artisan config:cache
echo.
pause
