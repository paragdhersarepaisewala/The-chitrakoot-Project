@echo off
title Shri Kamadgiri Peetham - Live Host
cd /d "%~dp0"

echo =======================================================================
echo    Launching Shri Kamadgiri Peetham Sewa Trust Live Cloudflare Host
echo =======================================================================
echo.

powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0host-online.ps1" %*

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [!] An error occurred while running the host script.
    pause
)
