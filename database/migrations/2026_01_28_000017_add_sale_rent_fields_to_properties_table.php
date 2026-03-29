<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->boolean('for_sale')->default(false)->after('tipo');
            $table->boolean('for_rent')->default(false)->after('for_sale');
            $table->decimal('sale_price', 12, 2)->nullable()->after('for_rent');
            $table->decimal('rent_price', 12, 2)->nullable()->after('sale_price');
        });

        DB::table('properties')->where('tipo', 'venta')->update([
            'for_sale' => true,
            'sale_price' => DB::raw('precio'),
        ]);
        DB::table('properties')->where('tipo', 'arriendo')->update([
            'for_rent' => true,
            'rent_price' => DB::raw('precio'),
        ]);
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['for_sale', 'for_rent', 'sale_price', 'rent_price']);
        });
    }
};
