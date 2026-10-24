$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
Push-Location $projectRoot

try {
    & "$PSScriptRoot\php-command.ps1" @('artisan', 'config:clear', '--ansi')
    if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
    & "$PSScriptRoot\php-command.ps1" @('vendor\bin\phpunit', '--colors=always')
    exit $LASTEXITCODE
} finally {
    Pop-Location
}
