<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CmsInstallCommand extends Command
{
    protected $signature = 'cms:install';
    protected $description = 'Interactive terminal CMS installation wizard';

    public function handle()
    {
        $this->info('=== Welcome to LaraCMS Installer ===');

        if (file_exists(storage_path('app/.installed'))) {
            if (!$this->confirm('LaraCMS is already installed. Do you want to reinstall?', false)) {
                return 0;
            }
        }

        $siteName = $this->ask('Enter Site Name', 'LaraCMS Site');
        $email = $this->ask('Enter Admin Email Address', 'admin@example.com');
        $password = $this->secret('Enter Admin Password');

        $this->info('Running database migrations...');
        Artisan::call('migrate --force');

        $this->info('Generating Application Key...');
        Artisan::call('key:generate');

        // Write lock file
        File::put(storage_path('app/.installed'), '1');

        $this->info('LaraCMS installed successfully!');
        return 0;
    }
}
