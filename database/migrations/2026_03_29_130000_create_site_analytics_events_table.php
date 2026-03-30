<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 30)->index();
            $table->string('page_path', 255)->nullable()->index();
            $table->text('page_url')->nullable();
            $table->text('target_url')->nullable();
            $table->string('target_text', 255)->nullable();
            $table->string('cta_key', 120)->nullable()->index();
            $table->text('referrer')->nullable();
            $table->string('utm_source')->nullable()->index();
            $table->string('utm_medium')->nullable()->index();
            $table->string('utm_campaign')->nullable()->index();
            $table->string('utm_content')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('session_id', 100)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_analytics_events');
    }
};
