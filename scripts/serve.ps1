param([switch] $SkipMigrate)

$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$databasePath = Join-Path $projectRoot 'database\database.sqlite'
Push-Location $projectRoot

try {
    if (-not (Test-Path -LiteralPath '.env')) {
        Copy-Item -LiteralPath '.env.example' -Destination '.env'
        & "$PSScriptRoot\php-command.ps1" @('artisan', 'key:generate', '--ansi')
        if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
    }
    if (-not (Test-Path -LiteralPath $databasePath)) {
        New-Item -ItemType File -Path $databasePath | Out-Null
    }
    if (-not $SkipMigrate) {
        & "$PSScriptRoot\php-command.ps1" @('artisan', 'migrate', '--seed', '--ansi')
        if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
    }
    Write-Host ''
    Write-Host 'InvestAI is available at http://127.0.0.1:8000'
    Write-Host 'Press Ctrl+C to stop the local server.'
    Set-Location (Join-Path $projectRoot 'public')
    & "$PSScriptRoot\php-command.ps1" @(
        '-S', '127.0.0.1:8000',
        '-t', '.',
        (Join-Path $projectRoot 'vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php')
    )
    exit $LASTEXITCODE
} finally {
    Pop-Location
}
