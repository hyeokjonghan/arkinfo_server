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
        Schema::create('lostark_produce_item', function (Blueprint $table) {
            $table->id();
            $table->string('item_code',20);
            $table->string('produce_item_name')->comment('수동으로 넣어줄 노출될 아이템 명칭');
            $table->enum('produce_type',[0,1,2,3,4,5,6,7,8,9])->comment('제작 타입');
            $table->integer('produce_cost')->nullable()->comment('제작시 필요 활동력 (없을 수 있음)');
            $table->enum('produce_price_type', [0,1,2])->default(0)->comment('제작시 필요 재화 타입, 0: 없음, 1: 골드, 2: 실랭');
            $table->integer('produce_price')->nullable()->comment('제작시 필요 비용');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loastark_produce_item');
    }
};
