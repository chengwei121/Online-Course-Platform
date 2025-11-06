@echo off
echo Starting Laravel Queue Worker...
echo ================================
echo.
echo Queue worker is running. Keep this window open.
echo Press Ctrl+C to stop the queue worker.
echo.
php artisan queue:work --tries=3 --timeout=90
