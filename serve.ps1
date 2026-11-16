# Script to run Laravel development server locally
Write-Host "====================================================" -ForegroundColor Cyan
Write-Host " Starting Shri Kamadgiri Peetham Sewa Trust Server " -ForegroundColor Yellow
Write-Host "====================================================" -ForegroundColor Cyan

$phpExe = $null

if (Get-Command "php" -ErrorAction SilentlyContinue) {
    $phpExe = "php"
} elseif (Test-Path "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe") {
    $phpExe = "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe"
} elseif (Test-Path "$env:USERPROFILE\.config\herd-lite\bin\php.exe") {
    $phpExe = "$env:USERPROFILE\.config\herd-lite\bin\php.exe"
}

if (-not $phpExe) {
    Write-Host "[ERROR] PHP executable could not be found." -ForegroundColor Red
    Write-Host "Please check that Laragon is installed in C:\laragon." -ForegroundColor Red
    exit 1
}

Write-Host "Using PHP: $phpExe" -ForegroundColor Green
Write-Host "Local URL: http://127.0.0.1:8000" -ForegroundColor Cyan
Write-Host "Press Ctrl+C to stop the server.`n" -ForegroundColor DarkGray

& $phpExe artisan serve --host=127.0.0.1 --port=8000
