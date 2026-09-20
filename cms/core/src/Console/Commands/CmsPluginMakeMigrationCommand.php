<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CmsPluginMakeMigrationCommand extends Command
{
    protected $signature = 'cms:plugin:make-migration {plugin} {name} {--create=} {--table=}';
    protected $description = 'Create a new migration file for a specific plugin';

    public function handle()
    {
        $plugin = $this->argument('plugin');
        $name = Str::snake($this->argument('name'));
        
        $pluginPath = base_path("cms-content/plugins/{$plugin}");
        
        if (!File::isDirectory($pluginPath)) {
            $this->error("The plugin directory '{$plugin}' does not exist.");
            return 1;
        }

        $migrationsPath = "{$pluginPath}/migrations";
        if (!File::isDirectory($migrationsPath)) {
            File::makeDirectory($migrationsPath, 0755, true);
        }

        $prefix = date('Y_m_d_His');
        $filePath = "{$migrationsPath}/{$prefix}_{$name}.php";

        $createTable = $this->option('create');
        $updateTable = $this->option('table');

        if ($createTable) {
            $stub = $this->getCreateStub($createTable);
        } elseif ($updateTable) {
            $stub = $this->getUpdateStub($updateTable);
        } else {
            $stub = $this->getBlankStub();
        }

        File::put($filePath, $stub);

        $this->info("Migration [{$filePath}] created successfully.");
        return 0;
    }

    protected function getCreateStub($table)
    {
        return "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Schema::create('{$table}', function (Blueprint \$table) {\n            \$table->id();\n            \$table->timestamps();\n        });\n    }\n\n    public function down(): void\n    {\n        Schema::dropIfExists('{$table}');\n    }\n};\n";
    }

    protected function getUpdateStub($table)
    {
        return "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Schema::table('{$table}', function (Blueprint \$table) {\n            //\n        });\n    }\n\n    public function down(): void\n    {\n        Schema::table('{$table}', function (Blueprint \$table) {\n            //\n        });\n    }\n};\n";
    }

    protected function getBlankStub()
    {
        return "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        //\n    }\n\n    public function down(): void\n    {\n        //\n    }\n};\n";
    }
}
