$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$portableNodeDirectory = Join-Path $projectRoot '.tools\node\node-v24.22.0-win-x64'
$portableNpm = Join-Path $portableNodeDirectory 'npm.cmd'

Push-Location $projectRoot

try {
    if (Test-Path -LiteralPath $portableNpm) {
        $env:Path = "$portableNodeDirectory;$env:Path"
        & $portableNpm run build -- --configLoader native
    } else {
        & npm run build -- --configLoader native
    }
    exit $LASTEXITCODE
} finally {
    Pop-Location
}
