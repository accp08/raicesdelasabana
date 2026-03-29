<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->string('descripcion_corta', 500)->nullable();
            $table->longText('descripcion')->nullable();
            $table->decimal('precio', 12, 2)->nullable();
            $table->string('ciudad', 120);
            $table->string('direccion')->nullable();
            $table->string('tipo', 20);
            $table->string('estado', 30)->default('disponible');
            $table->unsignedSmallInteger('habitaciones')->nullable();
            $table->unsignedSmallInteger('banos')->nullable();
            $table->decimal('area_m2', 10, 2)->nullable();
            $table->string('imagen_principal')->nullable();
            $table->json('galeria')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 255)->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
