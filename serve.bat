@echo off

echo Starting Laravel server...
start "" /B php artisan serve
echo Starting Queue Worker...
start "" /B php artisan queue:work

timeout /t 2 >nul

echo Opening browser...
start http://127.0.0.1:8000
