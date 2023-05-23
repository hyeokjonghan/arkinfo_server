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
        Schema::create('lostark_item_material', function (Blueprint $table) {
            $table->id();
            $table->string('item_code',20)->comment('키값, 로아 아이템 코드 번호');
            $table->integer('cost')->comment('제작시 필요한 아이템 개수');
            $table->enum('produce_type', [0,1,2,3,4,5,6,7,8,9])->comment('제작 타입 구분, 한가지 아이템을 여러가지 방식으로 조합 가능 함');

            $table->timestamps();
            $table->softDeletes();
            // 데이터 가져올 때, produce_type, item_code를 그룹별로 묶는다? ㄴㄴ? 아 그냥 기준을 이거로 두고.. 검색이 힘든데 그럼..
            // item_code로 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lostark_item_material');
    }
};
