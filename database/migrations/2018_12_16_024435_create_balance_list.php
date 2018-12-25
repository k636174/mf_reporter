<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_list', function (Blueprint $table) {
            // 収支一覧
            $table->increments('id');
            $table->integer('user_id',false,10)->comment('ユーザーID');
            $table->boolean('is_confirmed')->comment('計算対象');
            $table->date('use_date')->comment("利用日");
            $table->string('body')->comment('内容');
            $table->integer('amount')->commnet('金額');
            $table->string('financial_company')->comment('保有金融機関');
            $table->string('category_1')->comment('大項目');
            $table->string('category_2')->comment('中項目');
            $table->string('memo')->comment('メモ');
            $table->boolean('is_transfer')->comment('振替フラグ');
            $table->string('mf_id')->comment('マネーフォワード上のユニーク値');
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
        Schema::drop('BalanceList');
    }
}
