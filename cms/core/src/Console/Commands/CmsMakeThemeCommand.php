<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CmsMakeThemeCommand extends Command
{
    protected $signature = 'cms:make:theme {name}';
    protected $description = 'Generate a new theme boilerplate';

    public function handle()
    {
        $name = $this->argument('name');
        $slug = strtolower($name);
        $path = base_path("cms-content/themes/{$slug}");

        if (File::isDirectory($path)) {
            $this->error("Theme '{$name}' already exists.");
            return 1;
        }

        File::makeDirectory($path, 0755, true);
        File::makeDirectory("{$path}/layouts", 0755, true);

        // Create index.blade.php
        File::put("{$path}/index.blade.php", "<!-- Index Template for {$name} Theme -->\n<h1>Welcome to {$name}</h1>\n");
        // Create theme.json metadata
        File::put("{$path}/theme.json", json_encode([
            'name' => $name,
            'slug' => $slug,
            'version' => '1.0.0',
            'author' => 'Developer',
        ], JSON_PRETTY_PRINT));

        $this->info("Theme '{$name}' generated successfully at: cms-content/themes/{$slug}");
        return 0;
    }
}
