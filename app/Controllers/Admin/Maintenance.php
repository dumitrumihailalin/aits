<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Maintenance extends BaseController
{
    public function index()
    {
        return view('admin/maintenance/index', [
            'title' => 'Maintenance',
        ]);
    }

    public function run()
    {
        $action = $this->request->getPost('action');
        $output = [];
        $ok     = true;

        switch ($action) {
            case 'cache_clear':
                $result = $this->runSpark('cache:clear');
                $output[] = $result;
                // Also wipe the writable/cache directory manually
                $this->deleteDir(WRITEPATH . 'cache', ['index.html']);
                $output[] = 'writable/cache files deleted.';
                break;

            case 'debugbar_clear':
                $result = $this->runSpark('debugbar:clear');
                $output[] = $result;
                break;

            case 'logs_clear':
                $result = $this->runSpark('logs:clear');
                $output[] = $result;
                break;

            case 'optimize':
                $result = $this->runSpark('optimize');
                $output[] = $result;
                break;

            case 'deoptimize':
                // Remove the optimize cache files so dev mode works again
                $files = [
                    WRITEPATH . 'cache/framework/config.php',
                    WRITEPATH . 'cache/framework/routes.php',
                    WRITEPATH . 'cache/framework/services.php',
                ];
                foreach ($files as $file) {
                    if (is_file($file)) {
                        @unlink($file);
                        $output[] = 'Deleted: ' . basename($file);
                    }
                }
                if (empty($output)) {
                    $output[] = 'No optimize cache files found.';
                }
                break;

            case 'opcache_reset':
                if (function_exists('opcache_reset')) {
                    opcache_reset();
                    $output[] = 'OPcache reset successfully.';
                } else {
                    $output[] = 'OPcache is not enabled on this server.';
                    $ok = false;
                }
                break;

            case 'composer_optimize':
                $result = $this->runCmd('composer dump-autoload --optimize --no-dev');
                $output[] = $result;
                break;

            case 'clear_all':
                $steps = [
                    ['spark', 'cache:clear'],
                    ['spark', 'debugbar:clear'],
                    ['spark', 'logs:clear'],
                ];
                foreach ($steps as [$type, $cmd]) {
                    $output[] = $this->runSpark($cmd);
                }
                $this->deleteDir(WRITEPATH . 'cache', ['index.html']);
                $output[] = 'writable/cache files deleted.';
                if (function_exists('opcache_reset')) {
                    opcache_reset();
                    $output[] = 'OPcache reset.';
                }
                break;

            case 'go_production':
                $this->runSpark('cache:clear');
                $this->runSpark('debugbar:clear');
                $this->deleteDir(WRITEPATH . 'cache', ['index.html']);
                $optimize = $this->runSpark('optimize');
                $output[] = $optimize;
                $composer = $this->runCmd('composer dump-autoload --optimize --no-dev');
                $output[] = $composer;
                if (function_exists('opcache_reset')) {
                    opcache_reset();
                    $output[] = 'OPcache reset.';
                }
                $output[] = 'Done. Set CI_ENVIRONMENT=production in your .env file.';
                break;

            default:
                $output[] = 'Unknown action.';
                $ok = false;
        }

        return $this->response->setJSON([
            'ok'     => $ok,
            'output' => implode("\n", array_filter($output)),
        ]);
    }

    // ── Helpers ───────────────────────────────────────────

    private function runSpark(string $command): string
    {
        $php  = PHP_BINARY;
        $root = ROOTPATH;
        $cmd  = "\"{$php}\" \"{$root}spark\" {$command} 2>&1";
        $out  = shell_exec($cmd);
        return trim($out ?? "Could not run: spark {$command}");
    }

    private function runCmd(string $command): string
    {
        $root = ROOTPATH;
        $cmd  = "cd \"{$root}\" && {$command} 2>&1";
        $out  = shell_exec($cmd);
        return trim($out ?? "Could not run: {$command}");
    }

    private function deleteDir(string $path, array $skip = []): void
    {
        if (! is_dir($path)) {
            return;
        }
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file->isFile() && ! in_array($file->getFilename(), $skip, true)) {
                @unlink($file->getRealPath());
            }
        }
    }
}
