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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('navigation_label')->nullable();
            $table->text('description')->nullable();
            $table->string('page_type')->default('standard')->index();
            $table->string('status')->default('draft')->index();
            $table->boolean('is_system_page')->default(false);
            $table->boolean('deletion_protected')->default(false);
            $table->boolean('show_in_navigation')->default(false);
            $table->boolean('show_in_footer')->default(false);
            $table->boolean('index_by_search_engines')->default(true);
            $table->json('blocks')->nullable();
            $table->unsignedBigInteger('featured_media_id')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('open_graph_title')->nullable();
            $table->text('open_graph_description')->nullable();
            $table->unsignedBigInteger('open_graph_media_id')->nullable();
            $table->boolean('robots_follow')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_publish_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
