<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('sale_currency', 3)->default('COP')->after('sale_price');
            $table->string('rent_currency', 3)->default('COP')->after('rent_price');
        });

        DB::table('properties')
            ->whereNull('sale_currency')
            ->update(['sale_currency' => 'COP']);

        DB::table('properties')
            ->whereNull('rent_currency')
            ->update(['rent_currency' => 'COP']);
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['sale_currency', 'rent_currency']);
        });
    }
};
