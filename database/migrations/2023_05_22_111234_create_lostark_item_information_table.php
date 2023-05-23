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
        Schema::create('lostark_item_information', function (Blueprint $table) {
            $table->string('item_code',20)->primary()->index()->comment('키값, 로아 아이템 코드 번호');
            $table->string('item_name',100)->comment('아이템 명');
            $table->string('item_grade',10)->comment('아이템 등급')->nullable();
            $table->string('item_icon',255)->nullable()->comment('로아 cnd 아이콘 경로');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lostark_item_information');
    }
};
