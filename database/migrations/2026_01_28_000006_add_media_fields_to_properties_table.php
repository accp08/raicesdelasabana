<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('imagen_principal_thumb')->nullable()->after('imagen_principal');
            $table->json('galeria_thumbs')->nullable()->after('galeria');
            $table->string('youtube_url')->nullable()->after('galeria_thumbs');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['imagen_principal_thumb', 'galeria_thumbs', 'youtube_url']);
        });
    }
};
