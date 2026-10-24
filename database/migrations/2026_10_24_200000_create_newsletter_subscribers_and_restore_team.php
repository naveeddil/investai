<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->boolean('monthly_newsletter')->default(true);
            $table->boolean('weekly_tips')->default(false);
            $table->string('status')->default('pending')->index();
            $table->string('confirmation_token_hash', 64)->nullable()->unique();
            $table->string('manage_token_hash', 64)->unique();
            $table->text('manage_token');
            $table->string('consent_version')->default('newsletter-v1');
            $table->timestamp('consented_at');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });
        Schema::table('articles', fn (Blueprint $table) => $table->timestamp('weekly_emailed_at')->nullable()->index());

        $page = DB::table('pages')->where('slug', 'about')->first(['id', 'blocks']);
        $blocks = $page ? json_decode((string) $page->blocks, true) : null;
        if (is_array($blocks) && ! collect($blocks)->contains(fn (array $block): bool => ($block['type'] ?? null) === 'team')) {
            $team = ['type' => 'team', 'data' => ['eyebrow' => 'The people behind InvestAI', 'heading' => 'Meet the team.', 'text' => 'Finance, technology and research experience brought together around clearer, more responsible investment decisions.', 'background' => 'surface']];
            $position = collect($blocks)->search(fn (array $block): bool => ($block['type'] ?? null) === 'cta');
            $position === false ? $blocks[] = $team : array_splice($blocks, $position, 0, [$team]);
            DB::table('pages')->where('id', $page->id)->update(['blocks' => json_encode($blocks), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::table('articles', fn (Blueprint $table) => $table->dropColumn('weekly_emailed_at'));
        Schema::dropIfExists('newsletter_subscribers');
    }
};
