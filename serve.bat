@echo off
setlocal

echo ====================================================
echo Starting Shri Kamadgiri Peetham Sewa Trust Website
echo ====================================================

REM 1. Check if php is available in PATH
where php >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    echo Using system PHP...
    php artisan serve --host=127.0.0.1 --port=8000
    goto end
)

REM 2. Check Laragon PHP
if exist "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" (
    echo Using Laragon PHP (8.3.33)...
    "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" artisan serve --host=127.0.0.1 --port=8000
    goto end
)

REM 3. Check Herd-Lite PHP
if exist "%USERPROFILE%\.config\herd-lite\bin\php.exe" (
    echo Using Herd-Lite PHP...
    "%USERPROFILE%\.config\herd-lite\bin\php.exe" artisan serve --host=127.0.0.1 --port=8000
    goto end
)

echo [ERROR] PHP executable not found.
echo Please ensure Laragon is installed or add PHP to your Windows PATH.
pause

:end
endlocal
