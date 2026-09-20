<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CmsPluginMakeControllerCommand extends Command
{
    protected $signature = 'cms:plugin:make-controller {plugin} {name}';
    protected $description = 'Create a new controller class for a specific plugin';

    public function handle()
    {
        $plugin = $this->argument('plugin');
        $name = $this->argument('name');
        
        $pluginPath = base_path("cms-content/plugins/{$plugin}");
        
        if (!File::isDirectory($pluginPath)) {
            $this->error("The plugin directory '{$plugin}' does not exist.");
            return 1;
        }

        $controllersPath = "{$pluginPath}/src/Http/Controllers";
        if (!File::isDirectory($controllersPath)) {
            File::makeDirectory($controllersPath, 0755, true);
        }

        // Determine namespace and file path
        $name = str_replace('/', '\\', $name);
        $parts = explode('\\', $name);
        $className = array_pop($parts);
        
        $subNamespace = count($parts) > 0 ? '\\' . implode('\\', $parts) : '';
        $subPath = count($parts) > 0 ? '/' . implode('/', $parts) : '';
        
        if ($subPath && !File::isDirectory($controllersPath . $subPath)) {
            File::makeDirectory($controllersPath . $subPath, 0755, true);
        }

        $filePath = "{$controllersPath}{$subPath}/{$className}.php";
        
        if (File::exists($filePath)) {
            $this->error("Controller '{$name}' already exists in plugin '{$plugin}'.");
            return 1;
        }

        $namespaceName = Str::studly($plugin);
        $fullNamespace = "Plugin\\{$namespaceName}\\Http\\Controllers{$subNamespace}";

        $stub = "<?php\n\nnamespace {$fullNamespace};\n\nuse Illuminate\Routing\Controller;\nuse Illuminate\Http\Request;\n\nclass {$className} extends Controller\n{\n    //\n}\n";

        File::put($filePath, $stub);

        $this->info("Controller [{$filePath}] created successfully.");
        return 0;
    }
}
