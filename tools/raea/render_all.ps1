# Render all Social Share Cards using raea and raea-bevy
$cards = @(
    "home",
    "about",
    "leadership",
    "seva",
    "chitrakoot",
    "pooja",
    "events",
    "blog",
    "gallery",
    "contact",
    "donate",
    "volunteer",
    "privacy",
    "services",
    "aarti",
    "atithi-vishram",
    "temple-booking"
)

$scriptPath = "tools/raea/social_cards.tsx"
$outputDir = "public/images/og"

if (-not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Force -Path $outputDir | Out-Null
}

foreach ($id in $cards) {
    $outFile = "$outputDir/og-$id.png"
    Write-Host "Rendering $id to $outFile..." -ForegroundColor Cyan
    raea render $scriptPath --composition $id --frame 0 -o $outFile
    if (Test-Path $outFile) {
        $size = (Get-Item $outFile).Length
        Write-Host " [OK] $outFile rendered ($size bytes)" -ForegroundColor Green
    } else {
        Write-Host " [FAILED] $outFile failed to render" -ForegroundColor Red
    }
}
Write-Host "All social share cards rendered successfully!" -ForegroundColor Green
