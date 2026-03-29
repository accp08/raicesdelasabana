<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedTinyInteger('estrato')->nullable()->after('area_m2');
            $table->boolean('tiene_parqueadero')->default(false)->after('estrato');
            $table->boolean('tiene_bodega')->default(false)->after('tiene_parqueadero');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['estrato', 'tiene_parqueadero', 'tiene_bodega']);
        });
    }
};
