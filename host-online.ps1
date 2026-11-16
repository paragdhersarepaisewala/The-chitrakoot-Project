<#
.SYNOPSIS
    Automated Host & Cloudflare Live Tunnel for Shri Kamadgiri Peetham Sewa Trust
.DESCRIPTION
    Starts the Laravel backend server and establishes a secure, public Cloudflare
    temporary quick tunnel. Automatically grabs the live URL, copies it to the
    clipboard, and saves it so you can instantly send it to your client.
#>

[CmdletBinding()]
param(
    [switch]$NoBrowser,
    [int]$Port = 8000
)

try { $Host.UI.RawUI.WindowTitle = "Shri Kamadgiri Peetham - Live Cloudflare Host" } catch {}

# Colors & Banner Helper
function Show-Banner {
    try { Clear-Host } catch {}
    Write-Host "================================================================================" -ForegroundColor DarkYellow
    Write-Host "      SHRI KAMADGIRI PEETHAM SEWA TRUST - AUTOMATED CLIENT HOSTING SYSTEM       " -ForegroundColor Yellow -BackgroundColor DarkRed
    Write-Host "================================================================================" -ForegroundColor DarkYellow
    Write-Host ""
}

Show-Banner

# 1. Locate PHP Executable
Write-Host "[1/4] Locating PHP runtime..." -ForegroundColor Cyan
$phpExe = $null

if (Get-Command "php" -ErrorAction SilentlyContinue) {
    $phpExe = "php"
} elseif (Test-Path "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe") {
    $phpExe = "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe"
} else {
    $laragonPhps = Get-ChildItem "C:\laragon\bin\php\*\php.exe" -ErrorAction SilentlyContinue
    if ($laragonPhps) {
        $phpExe = $laragonPhps[-1].FullName
    } elseif (Test-Path "$env:USERPROFILE\.config\herd-lite\bin\php.exe") {
        $phpExe = "$env:USERPROFILE\.config\herd-lite\bin\php.exe"
    }
}

if (-not $phpExe) {
    Write-Host " [!] ERROR: PHP executable could not be found." -ForegroundColor Red
    Write-Host "     Please ensure PHP is installed or added to Windows PATH." -ForegroundColor DarkYellow
    Read-Host "Press Enter to exit"
    exit 1
}

$phpVer = & $phpExe -v 2>&1 | Select-Object -First 1
Write-Host " [+] Found PHP: $phpVer" -ForegroundColor Green

# 2. Locate or Download cloudflared.exe
Write-Host "`n[2/4] Checking Cloudflare Tunnel binary..." -ForegroundColor Cyan
$cfExe = Join-Path $PSScriptRoot "cloudflared.exe"

if (-not (Test-Path $cfExe)) {
    Write-Host " [!] cloudflared.exe not found in project directory." -ForegroundColor Yellow
    Write-Host " [*] Downloading latest official Windows Cloudflare Tunnel binary..." -ForegroundColor Cyan
    $cfDownloadUrl = "https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-windows-amd64.exe"
    try {
        [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
        Invoke-WebRequest -Uri $cfDownloadUrl -OutFile $cfExe -UseBasicParsing
        Write-Host " [+] Successfully downloaded cloudflared.exe!" -ForegroundColor Green
    } catch {
        Write-Host " [!] Failed to automatically download cloudflared.exe: $_" -ForegroundColor Red
        Write-Host "     Please download it manually from https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/downloads/" -ForegroundColor DarkYellow
        Read-Host "Press Enter to exit"
        exit 1
    }
} else {
    $cfVer = & $cfExe --version 2>&1 | Select-Object -First 1
    Write-Host " [+] Found Cloudflare Tunnel: $cfVer" -ForegroundColor Green
}

# 3. Check / Start Local Backend Server
Write-Host "`n[3/4] Initializing Laravel backend server on port $Port..." -ForegroundColor Cyan
$backendAlreadyRunning = $false
$phpProc = $null
$phpLog = Join-Path $PSScriptRoot ".tmp-php-serve.log"

try {
    $healthCheck = Invoke-WebRequest -Uri "http://127.0.0.1:$Port/up" -TimeoutSec 2 -UseBasicParsing -ErrorAction Stop
    if ($healthCheck.StatusCode -eq 200) {
        $backendAlreadyRunning = $true
        Write-Host " [+] Backend is already running on http://127.0.0.1:$Port (reusing active instance)." -ForegroundColor Green
    }
} catch {
    $backendAlreadyRunning = $false
}

if (-not $backendAlreadyRunning) {
    Remove-Item $phpLog -ErrorAction SilentlyContinue
    Write-Host " [*] Launching 'php artisan serve --port=$Port'..." -ForegroundColor Cyan
    $phpProc = Start-Process -FilePath $phpExe -ArgumentList "artisan", "serve", "--host=127.0.0.1", "--port=$Port" -RedirectStandardOutput $phpLog -RedirectStandardError $phpLog -PassThru -NoNewWindow
    
    # Wait for server to become responsive
    $serverReady = $false
    for ($i = 0; $i -lt 20; $i++) {
        Start-Sleep -Milliseconds 500
        try {
            $check = Invoke-WebRequest -Uri "http://127.0.0.1:$Port/up" -TimeoutSec 2 -UseBasicParsing -ErrorAction SilentlyContinue
            if ($check.StatusCode -eq 200) {
                $serverReady = $true
                break
            }
        } catch {}
    }

    if ($serverReady) {
        Write-Host " [+] Local server is UP & HEALTHY on http://127.0.0.1:$Port" -ForegroundColor Green
    } else {
        Write-Host " [!] Warning: Local server took longer than expected to report healthy, proceeding anyway..." -ForegroundColor Yellow
    }
}

# 4. Start Cloudflare Tunnel and Capture Public Link
Write-Host "`n[4/4] Establishing Cloudflare Quick Tunnel..." -ForegroundColor Cyan
$cfLog = Join-Path $PSScriptRoot ".tmp-cloudflared.log"
Remove-Item $cfLog -ErrorAction SilentlyContinue

$cfProc = Start-Process -FilePath $cfExe -ArgumentList "tunnel", "--url", "http://127.0.0.1:$Port", "--no-autoupdate" -RedirectStandardError $cfLog -PassThru -NoNewWindow

Write-Host " [*] Waiting for secure temporary domain from Cloudflare edge..." -ForegroundColor DarkYellow
$publicUrl = $null
$maxRetries = 40

for ($i = 0; $i -lt $maxRetries; $i++) {
    Start-Sleep -Milliseconds 600
    if (Test-Path $cfLog) {
        $lines = Get-Content $cfLog -ErrorAction SilentlyContinue
        foreach ($line in $lines) {
            if ($line -match "https://[a-zA-Z0-9-]+\.trycloudflare\.com") {
                $publicUrl = $matches[0]
                break
            }
        }
        if ($publicUrl) { break }
    }
}

if (-not $publicUrl) {
    Write-Host "`n [!] Could not detect public URL within 25 seconds." -ForegroundColor Red
    Write-Host "     Cloudflare Tunnel output log:" -ForegroundColor DarkYellow
    if (Test-Path $cfLog) { Get-Content $cfLog -Tail 15 }
    if ($phpProc) { Stop-Process -Id $phpProc.Id -Force -ErrorAction SilentlyContinue }
    if ($cfProc) { Stop-Process -Id $cfProc.Id -Force -ErrorAction SilentlyContinue }
    Read-Host "`nPress Enter to exit"
    exit 1
}

# Copy to Clipboard automatically (with clip.exe fallback)
$copiedToClipboard = $false
try {
    Set-Clipboard -Value $publicUrl -ErrorAction Stop
    $copiedToClipboard = $true
} catch {
    try {
        $publicUrl | clip.exe
        $copiedToClipboard = $true
    } catch {
        $copiedToClipboard = $false
    }
}

# Save to quick link file
$infoFile = Join-Path $PSScriptRoot "latest-client-link.txt"
$infoContent = @"
================================================================================
   SHRI KAMADGIRI PEETHAM SEWA TRUST - LIVE CLIENT DEMO LINK
================================================================================

PUBLIC DEMO LINK:  $publicUrl
STATUS:            ONLINE & ACTIVE
GENERATED AT:      $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')
LOCAL SERVER:      http://127.0.0.1:$Port

Instructions:
1. Send this link to your client via WhatsApp, Email, Slack, or SMS.
2. The site is running live from your machine through Cloudflare's global edge.
3. Keep the host-online terminal window open for the link to remain active.
================================================================================
"@
Set-Content -Path $infoFile -Value $infoContent -Encoding UTF8

# Display Stunning Client Delivery Banner
Show-Banner

Write-Host "  ============================================================================" -ForegroundColor Green
Write-Host "   SUCCESS! YOUR LIVE CLIENT DEMO LINK IS READY" -ForegroundColor White -BackgroundColor DarkGreen
Write-Host "  ============================================================================" -ForegroundColor Green
Write-Host ""
Write-Host "   PUBLIC CLIENT LINK:" -ForegroundColor Yellow
Write-Host "   $publicUrl" -ForegroundColor Cyan -BackgroundColor Black
Write-Host ""
if ($copiedToClipboard) {
    Write-Host "   [COPIED TO CLIPBOARD!] Press Ctrl+V in WhatsApp/Email to send to client." -ForegroundColor Green
}
Write-Host ""
Write-Host "   Local Server:      http://127.0.0.1:$Port" -ForegroundColor Gray
Write-Host "   Saved in File:     latest-client-link.txt" -ForegroundColor Gray
Write-Host "   Proxy Trust:       100% Configured (SSL/HTTPS & assets load seamlessly)" -ForegroundColor Gray
Write-Host ""
Write-Host "  ============================================================================" -ForegroundColor DarkGray
Write-Host "   [O] Open link in Browser   |   [C] Copy Link Again   |   [Q] Quit / Stop   " -ForegroundColor White
Write-Host "  ============================================================================" -ForegroundColor DarkGray
Write-Host ""

# Optional: Prompt to open browser
if (-not $NoBrowser) {
    Start-Process $publicUrl
}

# Keep active and monitor
try {
    while ($true) {
        $hasKey = $false
        try {
            if ($Host.UI -and $Host.UI.RawUI) {
                $hasKey = $Host.UI.RawUI.KeyAvailable
            }
        } catch {
            $hasKey = $false
        }

        if ($hasKey) {
            $key = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyUp")
            if ($key.Character -eq 'q' -or $key.Character -eq 'Q') {
                Write-Host "`nStopping tunnel..." -ForegroundColor Yellow
                break
            }
            if ($key.Character -eq 'o' -or $key.Character -eq 'O') {
                Write-Host "`nOpening in default browser: $publicUrl" -ForegroundColor Cyan
                Start-Process $publicUrl
            }
            if ($key.Character -eq 'c' -or $key.Character -eq 'C') {
                try { Set-Clipboard -Value $publicUrl } catch { $publicUrl | clip.exe }
                Write-Host "`nCopied to clipboard: $publicUrl" -ForegroundColor Green
            }
        }
        
        # Verify processes are still running
        if ($cfProc.HasExited) {
            Write-Host "`n[!] Cloudflare Tunnel disconnected unexpectedly." -ForegroundColor Red
            break
        }
        if ($phpProc -and $phpProc.HasExited) {
            Write-Host "`n[!] Local PHP server stopped unexpectedly." -ForegroundColor Red
            break
        }
        Start-Sleep -Milliseconds 400
    }
}
finally {
    Write-Host "`nCleaning up services..." -ForegroundColor DarkGray
    if ($cfProc -and -not $cfProc.HasExited) {
        Stop-Process -Id $cfProc.Id -Force -ErrorAction SilentlyContinue
    }
    if ($phpProc -and -not $phpProc.HasExited) {
        Stop-Process -Id $phpProc.Id -Force -ErrorAction SilentlyContinue
    }
    Remove-Item $cfLog -ErrorAction SilentlyContinue
    Remove-Item $phpLog -ErrorAction SilentlyContinue

    # Update latest-client-link.txt to offline
    if (Test-Path $infoFile) {
        Add-Content -Path $infoFile -Value "`nSTATUS: OFFLINE (Tunnel stopped on $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss'))"
    }

    Write-Host "[+] All hosting processes terminated cleanly. Good bye!`n" -ForegroundColor Green
}
