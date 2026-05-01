<?php

/**
 * Cache & Production Optimizer
 * Access: https://alinitservices.com/cache-clear.php?token=YOUR_SECRET_TOKEN
 *
 * Set your token below, then delete or restrict this file after use.
 */

define('SECRET_TOKEN', 'aits-clear-2025');   // ← change this

// ── Auth ──────────────────────────────────────────────────────────────────────
if (($_GET['token'] ?? '') !== SECRET_TOKEN) {
    http_response_code(403);
    die('403 Forbidden');
}

// ── Bootstrap CI4 (for cache() helper + WRITEPATH) ───────────────────────────
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(dirname(__DIR__));

require dirname(__DIR__) . '/vendor/codeigniter4/framework/system/bootstrap.php';

// ── Helpers ───────────────────────────────────────────────────────────────────
function run_spark(string $cmd): string
{
    $php  = PHP_BINARY;
    $root = ROOTPATH;
    $full = "\"{$php}\" \"{$root}spark\" {$cmd} 2>&1";
    return trim(shell_exec($full) ?? "⚠ Could not run: spark {$cmd}");
}

function delete_dir_files(string $path, array $skip = ['index.html', '.htaccess']): int
{
    if (! is_dir($path)) return 0;
    $count = 0;
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if ($file->isFile() && ! in_array($file->getFilename(), $skip, true)) {
            @unlink($file->getRealPath());
            $count++;
        }
    }
    return $count;
}

function run_cmd(string $cmd): string
{
    $root = ROOTPATH;
    $full = "cd \"{$root}\" && {$cmd} 2>&1";
    return trim(shell_exec($full) ?? "⚠ Could not run: {$cmd}");
}

// ── Run all tasks ─────────────────────────────────────────────────────────────
$results = [];
$start   = microtime(true);

// 1. CI application cache
$results['CI App Cache'] = run_spark('cache:clear');

// 2. Writable/cache directory
$n = delete_dir_files(WRITEPATH . 'cache');
$results['writable/cache'] = "Deleted {$n} file(s).";

// 3. Optimize cache files (config, routes, services — created by spark optimize)
$optimizeFiles = ['config.php', 'routes.php', 'services.php'];
$deleted = [];
foreach ($optimizeFiles as $f) {
    $path = WRITEPATH . 'cache/framework/' . $f;
    if (is_file($path)) { @unlink($path); $deleted[] = $f; }
}
$results['Optimize Cache'] = $deleted
    ? 'Deleted: ' . implode(', ', $deleted)
    : 'No optimize cache files found.';

// 4. Debugbar
$results['Debugbar'] = run_spark('debugbar:clear');

// 5. Logs
$results['Logs'] = run_spark('logs:clear');

// 6. OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
    $results['OPcache'] = 'Reset successfully.';
} else {
    $results['OPcache'] = 'Not enabled on this server.';
}

// 7. Composer autoloader
$results['Composer Autoload'] = run_cmd('composer dump-autoload --optimize --no-dev');

// 8. Spark optimize (caches config + routes + services for production)
$results['Spark Optimize'] = run_spark('optimize');

$elapsed = round(microtime(true) - $start, 2);

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cache Clear — Alin IT Services</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #0f1117; color: #e5e7eb; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
  .wrap { width: 100%; max-width: 680px; }
  h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
  .sub { font-size: 13px; color: #6b7280; margin-bottom: 24px; }
  .card { background: #1a1d27; border: 1px solid #2a2d3a; border-radius: 12px; padding: 20px; margin-bottom: 12px; }
  .row { display: flex; gap: 12px; align-items: flex-start; }
  .label { font-size: 13px; font-weight: 600; color: #9ca3af; min-width: 160px; padding-top: 2px; }
  .val { font-size: 13px; color: #d1fae5; font-family: 'Courier New', monospace; white-space: pre-wrap; word-break: break-all; }
  .val.warn { color: #fde68a; }
  .footer { font-size: 12px; color: #4b5563; margin-top: 16px; text-align: center; }
  .badge { display: inline-block; background: #10b981; color: #fff; font-size: 11px; font-weight: 700; border-radius: 20px; padding: 2px 10px; margin-bottom: 4px; }
</style>
</head>
<body>
<div class="wrap">
  <h1>🧹 Cache Cleared</h1>
  <p class="sub">All tasks completed in <?= $elapsed ?>s &mdash; <?= date('Y-m-d H:i:s') ?> UTC</p>

  <div class="card">
    <?php foreach ($results as $label => $output): ?>
    <div class="row" style="margin-bottom:14px;">
      <div class="label"><?= htmlspecialchars($label) ?></div>
      <div class="val <?= str_contains($output, '⚠') || str_contains(strtolower($output), 'error') ? 'warn' : '' ?>">
        <?= htmlspecialchars($output) ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <p class="footer">⚠ Delete or password-protect this file after use.</p>
</div>
</body>
</html>
