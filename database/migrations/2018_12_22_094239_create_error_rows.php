<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorRows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_rows', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_acknowledged')->default('0')->comment('対応済みフラグ');
            $table->integer('user_id',false,10)->comment('ユーザーID');
            $table->string('error_row')->comment('エラー行');
            $table->string('memo')->comment('メモ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('error_rows');
    }
}
