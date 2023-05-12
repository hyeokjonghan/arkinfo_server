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
        Schema::create('sync_fail', function (Blueprint $table) {
            $table->id();
            // mongodb table, key name, key value, fail type, check
            $table->string('table_name',50);
            $table->string('key_name',100);
            $table->string('key_value',255);
            $table->enum('fail_type',[1,2,3,4,5,6,7,8,9])
            ->comment("1: 단순 추가 실패, 2: 이미지 업로드 실패");
            $table->enum('check',[1,2])
            ->comment('1: 미확인, 2: 처리완료');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_fail');
    }
};
