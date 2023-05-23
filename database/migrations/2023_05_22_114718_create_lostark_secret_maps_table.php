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
        Schema::create('lostark_secret_maps', function (Blueprint $table) {
            $table->increments('map_id');
            $table->string('map_type_name',30)->comment('맵 종류 명. 파푸니카 베른 남부 볼다이크... 구분용. 수동입력 할 것임');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lostark_secret_maps');
    }
};
