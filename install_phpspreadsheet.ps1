# PhpSpreadsheet Installation Script
# This script downloads composer.phar and installs PhpSpreadsheet

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "PhpSpreadsheet Installation Script" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Check for composer.phar
$composerPhar = "composer.phar"
if (-not (Test-Path $composerPhar)) {
    Write-Host "Composer.phar not found, downloading..." -ForegroundColor Yellow
    try {
        Invoke-WebRequest -Uri "https://getcomposer.org/composer.phar" -OutFile $composerPhar -UseBasicParsing
        Write-Host "OK: Composer.phar downloaded" -ForegroundColor Green
    } catch {
        Write-Host "ERROR: Failed to download composer.phar: $_" -ForegroundColor Red
        Write-Host ""
        Write-Host "Alternative: Manually download from https://getcomposer.org/composer.phar" -ForegroundColor Yellow
        exit 1
    }
} else {
    Write-Host "OK: Composer.phar found" -ForegroundColor Green
}

Write-Host ""

# Check for PHP
Write-Host "Searching for PHP..." -ForegroundColor Yellow
$phpPaths = @(
    "php.exe",
    "C:\xampp\php\php.exe",
    "C:\wamp64\bin\php\php8.1.0\php.exe",
    "C:\wamp64\bin\php\php8.0.0\php.exe",
    "C:\wamp64\bin\php\php7.4.0\php.exe",
    "C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe",
    "C:\laragon\bin\php\php-8.0.28-Win32-vs16-x64\php.exe"
)

$phpExe = $null
foreach ($path in $phpPaths) {
    if (Test-Path $path) {
        $phpExe = $path
        Write-Host "OK: PHP found at: $path" -ForegroundColor Green
        break
    }
}

if (-not $phpExe) {
    # Check from PATH
    try {
        $null = Get-Command php -ErrorAction Stop
        $phpExe = "php"
        Write-Host "OK: PHP found in PATH" -ForegroundColor Green
    } catch {
        # PHP not found
    }
}

if (-not $phpExe) {
    Write-Host "ERROR: PHP not found!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please:" -ForegroundColor Yellow
    Write-Host "  1. Install XAMPP, WAMP, or Laragon" -ForegroundColor Yellow
    Write-Host "  2. Or add PHP to PATH" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Download PHP: https://windows.php.net/download/" -ForegroundColor Cyan
    exit 1
}

Write-Host ""
Write-Host "Checking PHP version..." -ForegroundColor Yellow
$phpVersion = & $phpExe --version 2>&1 | Select-Object -First 1
Write-Host $phpVersion -ForegroundColor Cyan
Write-Host ""

# Test composer
Write-Host "Testing composer..." -ForegroundColor Yellow
try {
    $composerVersion = & $phpExe $composerPhar --version 2>&1
    Write-Host "OK: Composer is working" -ForegroundColor Green
    Write-Host $composerVersion -ForegroundColor Cyan
} catch {
    Write-Host "ERROR: Cannot run composer: $_" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Installing PhpSpreadsheet..." -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Go to composer directory
$originalLocation = Get-Location
Set-Location "composer"

try {
    # Run composer require
    Write-Host "Installing PhpSpreadsheet package..." -ForegroundColor Yellow
    & $phpExe ..\$composerPhar require phpoffice/phpspreadsheet --no-interaction 2>&1 | Tee-Object -Variable output
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "=========================================" -ForegroundColor Green
        Write-Host "OK: PhpSpreadsheet installed successfully!" -ForegroundColor Green
        Write-Host "=========================================" -ForegroundColor Green
        Write-Host ""
        
        # Verify
        if (Test-Path "vendor\phpoffice\phpspreadsheet") {
            Write-Host "OK: Directory verified: vendor\phpoffice\phpspreadsheet" -ForegroundColor Green
        }
    } else {
        Write-Host ""
        Write-Host "ERROR: Installation failed!" -ForegroundColor Red
        Write-Host "Output: $output" -ForegroundColor Yellow
        Set-Location $originalLocation
        exit 1
    }
} catch {
    Write-Host ""
    Write-Host "ERROR: $_" -ForegroundColor Red
    Set-Location $originalLocation
    exit 1
}

Set-Location $originalLocation

Write-Host ""
Write-Host "B10 Excel integration is now ready!" -ForegroundColor Green
Write-Host ""
