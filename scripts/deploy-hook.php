<?php

/*
 * One-time staging deployment hook, uploaded by the GitHub Actions workflow.
 *
 * The workflow fills in the placeholders, uploads this file into public/ under
 * a random name next to the release archive in the application root, then
 * calls it over HTTPS until it answers DEPLOYMENT COMPLETE. The web server
 * cuts requests off after about 30 seconds, so each call does at most
 * TIME_BUDGET seconds of work, records its progress in a state file and
 * answers CONTINUE. The hook deletes itself when it finishes or fails.
 *
 * Phases:
 *  extract  unpack the archive into a staging directory, in batches
 *  install  replace the code directories wholesale (so deleted classes do not
 *           linger) and copy bootstrap/, public/ and the root files over the
 *           existing ones (keeping .env, storage/, .htaccess and .user.ini)
 *  cleanup  delete the old directories, including leftovers of failed runs
 *  artisan  run the Laravel post-deployment commands
 */

const DEPLOY_ID = '__DEPLOY_ID__';
const DEPLOY_TOKEN_SHA256 = '__DEPLOY_TOKEN_SHA256__';
const REPLACED_DIRECTORIES = ['app', 'config', 'database', 'resources', 'routes', 'vendor'];
const TIME_BUDGET = 15;
const EXTRACT_BATCH = 200;

ignore_user_abort(true);
set_time_limit(0);
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');

$root = dirname(__DIR__);
$archive = "{$root}/".DEPLOY_ID.'.zip';
$stage = "{$root}/.deploy-".DEPLOY_ID;
$stateFile = "{$root}/.deploy-state-".DEPLOY_ID.'.json';
$deadline = microtime(true) + TIME_BUDGET;

function finish(): void
{
    global $archive, $stateFile;
    @unlink($archive);
    @unlink($stateFile);
    @unlink(__FILE__);
}

function fail(string $message, int $status = 500): never
{
    finish();
    http_response_code($status);
    echo "FAILED: {$message}\n";
    exit(1);
}

function saveState(array $state): never
{
    global $stateFile;
    file_put_contents($stateFile, json_encode($state)) || fail('Cannot save the deployment state');
    echo "CONTINUE\n";
    exit(0);
}

/** Deletes a directory tree until the deadline; returns whether it is gone. */
function removeTree(string $path, float $deadline): bool
{
    if (is_link($path) || is_file($path)) {
        return unlink($path);
    }
    if (! is_dir($path)) {
        return true;
    }
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST,
    );
    foreach ($items as $item) {
        $item->isDir() && ! $item->isLink() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        if (microtime(true) > $deadline) {
            return false;
        }
    }

    return rmdir($path);
}

function copyTree(string $source, string $target): void
{
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

$state = is_file($stateFile) ? json_decode((string) file_get_contents($stateFile), true) : ['phase' => 'extract', 'next' => 0];
if (! is_array($state)) {
    fail('Cannot read the deployment state');
}

if ($state['phase'] === 'extract') {
    if (! class_exists(ZipArchive::class)) {
        fail('The PHP zip extension is not enabled on this server');
    }
    $zip = new ZipArchive();
    if ($zip->open($archive) !== true) {
        fail("Cannot open the release archive {$archive}");
    }
    $total = $zip->numFiles;
    while ($state['next'] < $total && microtime(true) < $deadline) {
        $names = [];
        for ($index = $state['next']; $index < min($total, $state['next'] + EXTRACT_BATCH); $index++) {
            $names[] = $zip->getNameIndex($index);
        }
        $zip->extractTo($stage, $names) || fail('Cannot extract the release archive');
        $state['next'] += count($names);
    }
    $zip->close();
    echo "Extracted {$state['next']} of {$total} archive entries\n";
    saveState($state['next'] < $total ? $state : ['phase' => 'install']);
}

if ($state['phase'] === 'install') {
    foreach (REPLACED_DIRECTORIES as $directory) {
        if (! is_dir("{$stage}/{$directory}")) {
            fail("Release is missing {$directory}/");
        }
        if (is_dir("{$root}/{$directory}")) {
            rename("{$root}/{$directory}", "{$root}/.retired-".DEPLOY_ID."-{$directory}")
                || fail("Cannot move the old {$directory}/ aside");
        }
        rename("{$stage}/{$directory}", "{$root}/{$directory}") || fail("Cannot move the new {$directory}/ into place");
    }
    copyTree($stage, $root);
    foreach (glob("{$root}/bootstrap/cache/*.php") ?: [] as $cached) {
        unlink($cached);
    }
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    echo "Installed release files\n";
    saveState(['phase' => 'cleanup']);
}

if ($state['phase'] === 'cleanup') {
    // Also catches directories left behind by earlier failed deployments.
    $leftovers = array_merge(glob("{$root}/.deploy-*", GLOB_ONLYDIR) ?: [], glob("{$root}/.retired-*", GLOB_ONLYDIR) ?: []);
    foreach ($leftovers as $leftover) {
        if (! removeTree($leftover, $deadline)) {
            echo "Removing old files\n";
            saveState($state);
        }
    }
    echo "Removed old files\n";
    saveState(['phase' => 'artisan']);
}

if ($state['phase'] === 'artisan') {
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

    finish();
    echo "\nDEPLOYMENT COMPLETE\n";
    exit(0);
}

fail('Unknown deployment state');
