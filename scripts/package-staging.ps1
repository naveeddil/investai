$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$artifactsRoot = Join-Path $projectRoot 'artifacts'
$releaseRoot = Join-Path $artifactsRoot 'investai-staging'
$archivePath = Join-Path $artifactsRoot 'investai-staging.zip'
$resolvedProjectRoot = [System.IO.Path]::GetFullPath($projectRoot)
$resolvedArtifactsRoot = [System.IO.Path]::GetFullPath($artifactsRoot)

if (-not $resolvedArtifactsRoot.StartsWith($resolvedProjectRoot, [System.StringComparison]::OrdinalIgnoreCase)) {
    throw 'The release directory must be inside the InvestAI project.'
}
if (Test-Path -LiteralPath $releaseRoot) { Remove-Item -LiteralPath $releaseRoot -Recurse -Force }
if (Test-Path -LiteralPath $archivePath) { Remove-Item -LiteralPath $archivePath -Force }
New-Item -ItemType Directory -Path $releaseRoot -Force | Out-Null

$releaseDirectories = @('app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'vendor')
$releaseFiles = @('artisan', 'composer.json', 'composer.lock', '.env.staging.example')

foreach ($directory in $releaseDirectories) {
    Copy-Item -LiteralPath (Join-Path $projectRoot $directory) -Destination $releaseRoot -Recurse
}
foreach ($file in $releaseFiles) {
    Copy-Item -LiteralPath (Join-Path $projectRoot $file) -Destination $releaseRoot
}
Remove-Item -LiteralPath (Join-Path $releaseRoot 'database\database.sqlite') -Force -ErrorAction SilentlyContinue
Get-ChildItem -LiteralPath (Join-Path $releaseRoot 'storage\logs') -File -ErrorAction SilentlyContinue | Remove-Item -Force
Get-ChildItem -LiteralPath (Join-Path $releaseRoot 'storage\framework\cache\data') -File -Recurse -ErrorAction SilentlyContinue | Remove-Item -Force
Get-ChildItem -LiteralPath (Join-Path $releaseRoot 'storage\framework\sessions') -File -ErrorAction SilentlyContinue | Remove-Item -Force
Get-ChildItem -LiteralPath (Join-Path $releaseRoot 'storage\framework\views') -File -ErrorAction SilentlyContinue | Remove-Item -Force
Compress-Archive -Path (Join-Path $releaseRoot '*') -DestinationPath $archivePath -CompressionLevel Optimal
Write-Host "Staging package created: $archivePath"
Write-Host 'Rename .env.staging.example to .env on the server and fill in its placeholders.'
