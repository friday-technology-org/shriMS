<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('version')->default('1.0.0');
            $table->string('author')->nullable();
            $table->string('description')->nullable();
            $table->string('screenshot')->nullable(); // filename relative to theme root
            $table->boolean('is_active')->default(false);
            $table->string('is_child_of')->nullable(); // parent theme slug
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
