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
        Schema::table('lostark_item_information', function (Blueprint $table) {
            $table->integer('category')->nullable();
            $table->integer('sub_category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lostark_item_information', function (Blueprint $table) {
            //
        });
    }
};
