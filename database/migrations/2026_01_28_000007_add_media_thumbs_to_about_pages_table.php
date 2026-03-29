<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->string('hero_image_thumb')->nullable()->after('hero_image');
            $table->string('section1_image_thumb')->nullable()->after('section1_image');
            $table->string('section2_image_thumb')->nullable()->after('section2_image');
            $table->string('section3_image_thumb')->nullable()->after('section3_image');
            $table->string('section4_image_thumb')->nullable()->after('section4_image');
            $table->string('section5_image_thumb')->nullable()->after('section5_image');
        });
    }

    public function down(): void
    {
        Schema::table('about_pages', function (Blueprint $table) {
            $table->dropColumn([
                'hero_image_thumb',
                'section1_image_thumb',
                'section2_image_thumb',
                'section3_image_thumb',
                'section4_image_thumb',
                'section5_image_thumb',
            ]);
        });
    }
};
