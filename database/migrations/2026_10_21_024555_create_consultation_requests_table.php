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
        Schema::create('consultation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('preferred_contact')->default('email');
            $table->string('preferred_time')->nullable();
            $table->string('topic')->index();
            $table->text('message')->nullable();
            $table->boolean('consent')->default(false);
            $table->string('status')->default('new')->index();
            $table->timestamp('submitted_at')->index();
            $table->string('ip_hash', 64)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_requests');
    }
};
