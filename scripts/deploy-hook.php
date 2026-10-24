<?php

/*
 * One-time staging deployment hook, uploaded by the GitHub Actions workflow.
 *
 * The workflow fills in the placeholders, uploads this file into public/ under
 * a random name next to the release archive in the application root, calls it
 * once over HTTPS and then removes it. The hook deletes itself as well, so it
 * only exists on the server for the duration of one deployment.
 *
 * Steps: extract the archive, replace the code directories wholesale (so
 * deleted classes do not linger), copy bootstrap/, public/ and the root files
 * over the existing ones (keeping .env, storage/, .htaccess and .user.ini),
 * then run the Laravel post-deployment commands.
 */

const DEPLOY_ID = '__DEPLOY_ID__';
const DEPLOY_TOKEN_SHA256 = '__DEPLOY_TOKEN_SHA256__';
const REPLACED_DIRECTORIES = ['app', 'config', 'database', 'resources', 'routes', 'vendor'];

ignore_user_abort(true);
set_time_limit(0);
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');

register_shutdown_function(static function (): void {
    @unlink(__FILE__);
});

function fail(string $message, int $status = 500): never
{
    http_response_code($status);
    echo "FAILED: {$message}\n";
    exit(1);
}

function removeTree(string $path): void
{
    if (is_link($path) || is_file($path)) {
        unlink($path);

        return;
    }
    if (! is_dir($path)) {
        return;
    }
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST,
    );
    foreach ($items as $item) {
        $item->isDir() && ! $item->isLink() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }
    rmdir($path);
}

function copyTree(string $source, string $target): void
{
    if (! is_dir($target) && ! mkdir($target, 0755, true)) {
        fail("Cannot create {$target}");
    }
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST,
    );
    foreach ($items as $item) {
        $destination = $target.substr($item->getPathname(), strlen($source));
        if ($item->isDir()) {
            if (! is_dir($destination) && ! mkdir($destination, 0755, true)) {
                fail("Cannot create {$destination}");
            }
        } elseif (! copy($item->getPathname(), $destination)) {
            fail("Cannot write {$destination}");
        }
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    fail('POST required', 405);
}
if (! hash_equals(DEPLOY_TOKEN_SHA256, hash('sha256', (string) ($_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? '')))) {
    fail('Invalid deployment token', 403);
}
if (! class_exists(ZipArchive::class)) {
    fail('The PHP zip extension is not enabled on this server');
}

$root = dirname(__DIR__);
$archive = "{$root}/".DEPLOY_ID.'.zip';
$stage = "{$root}/.deploy-".DEPLOY_ID;

if (! is_file($archive)) {
    fail("Release archive {$archive} was not uploaded");
}

$zip = new ZipArchive();
if ($zip->open($archive) !== true || ! $zip->extractTo($stage)) {
    fail('Cannot extract the release archive');
}
$zip->close();
unlink($archive);
echo "Extracted release\n";

$retired = [];
foreach (REPLACED_DIRECTORIES as $directory) {
    if (! is_dir("{$stage}/{$directory}")) {
        fail("Release is missing {$directory}/");
    }
    if (is_dir("{$root}/{$directory}")) {
        $old = "{$root}/.retired-".DEPLOY_ID."-{$directory}";
        rename("{$root}/{$directory}", $old) || fail("Cannot move the old {$directory}/ aside");
        $retired[] = $old;
    }
    rename("{$stage}/{$directory}", "{$root}/{$directory}") || fail("Cannot move the new {$directory}/ into place");
}
copyTree($stage, $root);
removeTree($stage);
foreach ($retired as $old) {
    removeTree($old);
}
foreach (glob("{$root}/bootstrap/cache/*.php") ?: [] as $cached) {
    unlink($cached);
}
if (function_exists('opcache_reset')) {
    opcache_reset();
}
echo "Installed release files\n";

require "{$root}/vendor/autoload.php";
$app = require "{$root}/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$commands = [['migrate', ['--force' => true]]];
if (($_POST['seed'] ?? '') === '1') {
    $commands[] = ['db:seed', ['--class' => 'ConsultancyContentSeeder', '--force' => true]];
}
$commands[] = ['optimize:clear', []];

foreach ($commands as [$command, $arguments]) {
    echo "\n$ php artisan {$command}\n";
    try {
        $exitCode = $kernel->call($command, $arguments);
    } catch (Throwable $exception) {
        fail("{$command}: ".$exception->getMessage());
    }
    echo $kernel->output();
    if ($exitCode !== 0) {
        fail("{$command} exited with code {$exitCode}");
    }
}

echo "\nDEPLOYMENT COMPLETE\n";
