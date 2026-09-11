@echo off
echo ========================================
echo   Setup Presensi Kita - Laravel
echo ========================================
echo.

if not exist .env copy .env.example .env

call composer install
if errorlevel 1 goto error

call php artisan key:generate
call php artisan migrate --seed
call php artisan storage:link
call php artisan optimize:clear

echo.
echo Setup selesai.
echo Jalankan: php artisan serve
echo Login Admin: admin / admin123
echo Login User : user / user123
pause
exit /b 0

:error
echo Setup gagal. Pastikan PHP dan Composer aktif di Laragon.
pause
exit /b 1
