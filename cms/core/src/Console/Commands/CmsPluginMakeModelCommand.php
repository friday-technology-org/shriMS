<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CmsPluginMakeModelCommand extends Command
{
    protected $signature = 'cms:plugin:make-model {plugin} {name} {-m|--migration : Create a new migration file for the model}';
    protected $description = 'Create a new Eloquent model class for a specific plugin';

    public function handle()
    {
        $plugin = $this->argument('plugin');
        $name = $this->argument('name');
        
        $pluginPath = base_path("cms-content/plugins/{$plugin}");
        
        if (!File::isDirectory($pluginPath)) {
            $this->error("The plugin directory '{$plugin}' does not exist.");
            return 1;
        }

        $modelsPath = "{$pluginPath}/src/Models";
        if (!File::isDirectory($modelsPath)) {
            File::makeDirectory($modelsPath, 0755, true);
        }

        $name = str_replace('/', '\\', $name);
        $parts = explode('\\', $name);
        $className = array_pop($parts);
        
        $subNamespace = count($parts) > 0 ? '\\' . implode('\\', $parts) : '';
        $subPath = count($parts) > 0 ? '/' . implode('/', $parts) : '';
        
        if ($subPath && !File::isDirectory($modelsPath . $subPath)) {
            File::makeDirectory($modelsPath . $subPath, 0755, true);
        }

        $filePath = "{$modelsPath}{$subPath}/{$className}.php";
        
        if (File::exists($filePath)) {
            $this->error("Model '{$name}' already exists in plugin '{$plugin}'.");
            return 1;
        }

        $namespaceName = Str::studly($plugin);
        $fullNamespace = "Plugin\\{$namespaceName}\\Models{$subNamespace}";

        $stub = "<?php\n\nnamespace {$fullNamespace};\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass {$className} extends Model\n{\n    protected \$guarded = [];\n}\n";

        File::put($filePath, $stub);
        $this->info("Model [{$filePath}] created successfully.");

        if ($this->option('migration')) {
            $table = Str::snake(Str::pluralStudly($className));
            $this->call('cms:plugin:make-migration', [
                'plugin' => $plugin,
                'name' => "create_{$table}_table",
                '--create' => $table
            ]);
        }

        return 0;
    }
}
