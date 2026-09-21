<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('posts', 'views_count')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->unsignedBigInteger('views_count')->default(0)->after('status');
            });
        }

        if (!Schema::hasTable('post_view_stats')) {
            Schema::create('post_view_stats', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->date('date');
                $table->unsignedBigInteger('views')->default(0);
                $table->timestamps();

                $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
                $table->unique(['post_id', 'date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_view_stats');

        if (Schema::hasColumn('posts', 'views_count')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('views_count');
            });
        }
    }
};
