<?php

/**
 * Full Cache & Session Reset
 * Place at: public_html/clear_cache.php  (NOT inside public_html/public/)
 * Access:   https://alinitservices.com/clear_cache.php?token=aits-clear-2025
 */

define('SECRET_TOKEN', 'aits-clear-2025');

if (($_GET['token'] ?? '') !== SECRET_TOKEN) {
    http_response_code(403);
    die('403 Forbidden');
}

// ── CI4 root path ─────────────────────────────────────────────────────────────
// Auto-detect: searches __DIR__ and up to two parent directories for app/ + vendor/
$root = null;
foreach ([__DIR__, dirname(__DIR__), dirname(dirname(__DIR__))] as $dir) {
    if (is_dir($dir . '/app') && is_dir($dir . '/vendor')) {
        $root = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        break;
    }
}
// Fallback: hardcoded server path (update if you move the project)
if ($root === null && is_dir('/home/bhroxcam/alinitservices/app')) {
    $root = '/home/bhroxcam/alinitservices/';
}
if ($root === null) {
    die('<pre>⚠ Cannot find CI4 root. Ensure app/ and vendor/ exist near this file.</pre>');
}

$writable = $root . 'writable' . DIRECTORY_SEPARATOR;
$spark    = $root . 'spark';
$php      = PHP_BINARY ?: 'php';

// ── Helpers ───────────────────────────────────────────────────────────────────
function run_spark(string $php, string $spark, string $cmd): string
{
    if (! is_file($spark)) return "⚠ spark not found at: {$spark}";
    $out = shell_exec("\"{$php}\" \"{$spark}\" {$cmd} 2>&1");
    return trim($out ?? "⚠ Could not run: spark {$cmd}");
}

function delete_dir_files(string $path, array $skip = ['index.html', '.htaccess', '.gitkeep']): int
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

// ── Tasks ─────────────────────────────────────────────────────────────────────
$results = [];
$start   = microtime(true);

// 1. CI application cache (spark)
$results['CI App Cache'] = run_spark($php, $spark, 'cache:clear');

// 2. writable/cache directory
$n = delete_dir_files($writable . 'cache');
$results['writable/cache'] = "Deleted {$n} file(s).";

// 3. Route/optimize cache files
$deleted = [];
foreach (['config.php', 'routes.php', 'services.php'] as $f) {
    $p = $writable . 'cache' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . $f;
    if (is_file($p)) { @unlink($p); $deleted[] = $f; }
}
$results['Route & Optimize Cache'] = $deleted
    ? 'Deleted: ' . implode(', ', $deleted)
    : 'No optimize cache files found.';

// 4. Session files (server-side)
$n = delete_dir_files($writable . 'session');
$results['Server Sessions'] = "Deleted {$n} session file(s).";

// 5. Browser cookies — expire every cookie the browser sent
$expired = [];
foreach ($_COOKIE as $name => $_val) {
    setcookie($name, '', [
        'expires'  => 1,
        'path'     => '/',
        'domain'   => '',
        'secure'   => ! empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    $expired[] = $name;
}
$results['Browser Cookies'] = $expired
    ? 'Expired: ' . implode(', ', $expired)
    : 'No cookies were set in this browser.';

// 6. Debugbar
$results['Debugbar'] = run_spark($php, $spark, 'debugbar:clear');

// 7. Logs
$results['Logs'] = run_spark($php, $spark, 'logs:clear');

// 8. Temp files
$n = delete_dir_files($writable . 'tmp');
$results['writable/tmp'] = $n > 0 ? "Deleted {$n} file(s)." : 'Nothing to clear.';

// 9. OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
    $results['OPcache'] = 'Reset successfully.';
} else {
    $results['OPcache'] = 'Not enabled on this server.';
}

// 10. LiteSpeed cache (lscache purge via header)
header('X-LiteSpeed-Purge: *');
$results['LiteSpeed Cache'] = 'Purge header sent (X-LiteSpeed-Purge: *).';

// 11. Spark optimize — rebuilds config/routes/services cache
$results['Spark Optimize'] = run_spark($php, $spark, 'optimize');

// ── Debug info ────────────────────────────────────────────────────────────────
$debug = [
    'This file'  => __FILE__,
    'CI4 root'   => $root,
    'writable'   => $writable . (is_dir($writable) ? ' ✓' : ' ✗ NOT FOUND'),
    'spark'      => $spark . (is_file($spark) ? ' ✓' : ' ✗ NOT FOUND'),
    'php'        => $php,
];

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
  .wrap { width: 100%; max-width: 740px; }
  h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
  .sub { font-size: 13px; color: #6b7280; margin-bottom: 24px; }
  .card { background: #1a1d27; border: 1px solid #2a2d3a; border-radius: 12px; padding: 20px; margin-bottom: 12px; }
  .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #4b5563; margin-bottom: 14px; }
  .row { display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px; }
  .row:last-child { margin-bottom: 0; }
  .label { font-size: 13px; font-weight: 600; color: #9ca3af; min-width: 185px; padding-top: 2px; flex-shrink: 0; }
  .val { font-size: 13px; color: #d1fae5; font-family: 'Courier New', monospace; white-space: pre-wrap; word-break: break-all; }
  .val.warn { color: #fde68a; }
  .val.muted { color: #6b7280; }
  .footer { font-size: 12px; color: #4b5563; margin-top: 16px; text-align: center; }
</style>
</head>
<body>
<div class="wrap">
  <h1>Cache &amp; Session Cleared</h1>
  <p class="sub">All tasks completed in <?= $elapsed ?>s &mdash; <?= date('Y-m-d H:i:s') ?> UTC</p>

  <div class="card">
    <div class="section-title">Results</div>
    <?php foreach ($results as $label => $output): ?>
    <div class="row">
      <div class="label"><?= htmlspecialchars($label) ?></div>
      <div class="val <?= (str_contains($output, '⚠') || str_contains(strtolower($output), 'error')) ? 'warn' : '' ?>">
        <?= htmlspecialchars($output) ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="card">
    <div class="section-title">Path Debug</div>
    <?php foreach ($debug as $label => $val): ?>
    <div class="row">
      <div class="label"><?= htmlspecialchars($label) ?></div>
      <div class="val muted"><?= htmlspecialchars($val) ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <p class="footer">Remove the Path Debug card and this file after confirming everything works.</p>
</div>
</body>
</html>
