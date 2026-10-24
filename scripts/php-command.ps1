param(
    [Parameter(Mandatory = $true, Position = 0, ValueFromRemainingArguments = $true)]
    [string[]] $Arguments
)

$projectRoot = Split-Path -Parent $PSScriptRoot
$portablePhp = Join-Path $projectRoot '.tools\php\php.exe'

if (Test-Path -LiteralPath $portablePhp) {
    $phpDirectory = Split-Path -Parent $portablePhp
    $phpArguments = @(
        '-d', "extension_dir=$(Join-Path $phpDirectory 'ext')",
        '-d', 'extension=openssl', '-d', 'extension=curl',
        '-d', 'extension=mbstring', '-d', 'extension=fileinfo',
        '-d', 'extension=intl', '-d', 'extension=zip',
        '-d', 'extension=pdo_sqlite'
    )
    & $portablePhp @phpArguments @Arguments
} else {
    & php @Arguments
}

exit $LASTEXITCODE
