<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id',false,10)->comment('ユーザーID');
            $table->string('file_path')->comment('ファイルパス');
            $table->boolean('is_working')->default('0')->comment('取り込み中フラグ');
            $table->boolean('is_complete')->default('0')->comment('取り込み完了フラグ');
            $table->string('memo')->nullable()->comment('メモ');
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
        Schema::dropIfExists('csv_files');
    }
}
