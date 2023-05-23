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
        Schema::create('lostark_market_price', function (Blueprint $table) {
            $table->string('item_code', 20)->primary();
            $table->integer('now_price')->comment('현재 최저가');
            $table->float('now_avg_price')->comment('현재 평균 거래가');
            $table->integer('bundle_count')->comment('판매 번들');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lostark_market_price');
    }
};
