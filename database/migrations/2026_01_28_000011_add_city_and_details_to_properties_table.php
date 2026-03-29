<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->after('ciudad')->constrained('cities')->nullOnDelete();
            $table->string('property_type', 50)->nullable()->after('city_id');
            $table->boolean('is_conjunto')->default(false)->after('property_type');
            $table->string('conjunto_nombre')->nullable()->after('is_conjunto');
            $table->string('barrio')->nullable()->after('conjunto_nombre');
            $table->string('contact_name')->nullable()->after('barrio');
            $table->string('contact_phone')->nullable()->after('contact_name');
            $table->string('contact_email')->nullable()->after('contact_phone');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn([
                'city_id',
                'property_type',
                'is_conjunto',
                'conjunto_nombre',
                'barrio',
                'contact_name',
                'contact_phone',
                'contact_email',
            ]);
        });
    }
};
