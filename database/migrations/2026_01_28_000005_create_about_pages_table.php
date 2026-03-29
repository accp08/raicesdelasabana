<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_image')->nullable();

            $table->string('section1_title')->nullable();
            $table->longText('section1_body')->nullable();
            $table->string('section1_image')->nullable();

            $table->string('section2_title')->nullable();
            $table->longText('section2_body')->nullable();
            $table->string('section2_image')->nullable();
            $table->json('section2_items')->nullable();

            $table->string('section3_title')->nullable();
            $table->longText('section3_body')->nullable();
            $table->string('section3_image')->nullable();
            $table->json('section3_items')->nullable();

            $table->string('section4_title')->nullable();
            $table->longText('section4_body')->nullable();
            $table->string('section4_image')->nullable();

            $table->string('section5_title')->nullable();
            $table->longText('section5_body')->nullable();
            $table->string('section5_image')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_role')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
