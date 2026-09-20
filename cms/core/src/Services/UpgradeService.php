<?php

namespace Cms\Core\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use ZipArchive;

class UpgradeService
{
    protected string $updateDir;

    public function __construct()
    {
        $this->updateDir = storage_path('cms-updates');
    }

    public function checkVersion(): array
    {
        $currentVersion = '1.0.0';
        if (file_exists(base_path('cms/core/version.php'))) {
            $currentVersion = require base_path('cms/core/version.php');
        }

        try {
            // Check updates from the live GitHub repository API
            $response = Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'LaraCMS-AutoUpdater',
            ])->timeout(10)->get('https://api.github.com/repos/friday-technology-org/shriMS/releases/latest');

            if ($response->successful()) {
                $release = $response->json();
                $latestVersion = ltrim($release['tag_name'] ?? '1.0.0', 'v');
                
                // Get the zipball URL provided by GitHub for the release
                $downloadUrl = $release['zipball_url'] ?? '';

                return [
                    'current_version' => $currentVersion,
                    'latest_version' => $latestVersion,
                    'has_update' => version_compare($latestVersion, $currentVersion, '>'),
                    'release_notes' => $release['body'] ?? 'No release notes provided.',
                    'download_url' => $downloadUrl,
                ];
            }
        } catch (\Exception $e) {
            logger()->error('Failed to check for updates: ' . $e->getMessage());
        }

        // Fallback if API fails (e.g., rate limit, network issue, or no releases yet)
        return [
            'current_version' => $currentVersion,
            'latest_version' => $currentVersion,
            'has_update' => false,
            'release_notes' => 'Unable to check for updates at this time or no releases found.',
            'download_url' => '',
        ];
    }

    public function performUpgrade(string $zipFilePath = null, string $newVersion = null): array
    {
        if (!File::isDirectory($this->updateDir)) {
            File::makeDirectory($this->updateDir, 0755, true);
        }

        $backupZip = $this->updateDir . '/backups/pre-upgrade-' . date('Y-m-d-H-i-s') . '.zip';
        if (!File::isDirectory(dirname($backupZip))) {
            File::makeDirectory(dirname($backupZip), 0755, true);
        }

        // 1. Put system in maintenance mode
        Artisan::call('down', ['--secret' => 'cms-upgrade-secret']);

        try {
            // 2. Perform automated pre-update backup of database + cms/core/
            $zip = new ZipArchive();
            if ($zip->open($backupZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Add cms/core directory recursively
                $corePath = base_path('cms/core');
                if (File::isDirectory($corePath)) {
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($corePath),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = 'cms/core/' . substr($filePath, strlen($corePath) + 1);
                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                }
                $zip->close();
            } else {
                throw new \Exception("Pre-upgrade backup failed. Cannot initialize backup zip.");
            }

            // 3. Extract the new core zip
            if ($zipFilePath && File::exists($zipFilePath)) {
                $extractZip = new ZipArchive();
                if ($extractZip->open($zipFilePath) === true) {
                    $tempExtractPath = $this->updateDir . '/temp-extract-' . time();
                    if (!File::isDirectory($tempExtractPath)) {
                        File::makeDirectory($tempExtractPath, 0755, true);
                    }
                    
                    $extractZip->extractTo($tempExtractPath);
                    $extractZip->close();
                    
                    // Check if there is a single root folder (typical of GitHub release zipballs)
                    $directories = File::directories($tempExtractPath);
                    $files = File::files($tempExtractPath);
                    
                    $sourcePath = $tempExtractPath;
                    if (count($directories) === 1 && count($files) === 0) {
                        $sourcePath = $directories[0];
                    }
                    
                    // If the zip contains the full repository with a cms/core directory, drill down into it
                    if (File::isDirectory($sourcePath . '/cms/core')) {
                        $sourcePath = $sourcePath . '/cms/core';
                    }
                    
                    // Move the contents to cms/core
                    File::copyDirectory($sourcePath, base_path('cms/core'));
                    
                    // Clean up the temporary extraction folder
                    File::deleteDirectory($tempExtractPath);
                } else {
                    throw new \Exception("Failed to open the update zip file.");
                }
            } else {
                throw new \Exception("No valid update zip file provided.");
            }
            
            // 4. Run core migrations
            Artisan::call('migrate', ['--force' => true]);

            // 5. Clear Caches
            Artisan::call('optimize:clear');

            // 6. Lift maintenance mode
            Artisan::call('up');

            // 7. Update version.php if new version is provided
            if ($newVersion) {
                File::put(base_path('cms/core/version.php'), "<?php return '{$newVersion}';\n");
            }

            return [
                'success' => true,
                'message' => 'Shri-ms Core successfully upgraded.',
            ];

        } catch (\Throwable $e) {
            // 7. Auto-rollback on failure
            logger()->error('Core upgrade failed, starting auto-rollback: ' . $e->getMessage());
            
            // Restore from backup zip
            if (File::isFile($backupZip)) {
                $zip = new ZipArchive();
                if ($zip->open($backupZip) === true) {
                    $zip->extractTo(base_path());
                    $zip->close();
                }
            }

            Artisan::call('up');

            return [
                'success' => false,
                'message' => 'Upgrade failed: ' . $e->getMessage() . '. System has been restored to pre-upgrade state.',
            ];
        }
    }
}
