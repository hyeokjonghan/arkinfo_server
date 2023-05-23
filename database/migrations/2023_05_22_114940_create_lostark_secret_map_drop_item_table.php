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
        Schema::create('lostark_secret_map_drop_item', function (Blueprint $table) {
            $table->id();
            $table->integer('map_id')->nullable()->comment('맵 종류 키값');
            $table->string('item_code', 20)->nullable()->comment('아이템 키값');
            $table->integer('drop_count')->nullable()->comment('드롭 아이템 개수');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lostark_secret_map_drop_item');
    }
};
