<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id',false,10)->comment('ユーザーID');
            $table->string('category_1')->comment('大項目');
            $table->string('category_2')->comment('中項目');
            $table->integer('amount')->commnet('金額');
            $table->integer('status')->commnet('1=eyear,2monthly');
            $table->integer('year')->commnet('予算年');
            $table->integer('month')->commnet('予算月');
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
        Schema::dropIfExists('budgets');
    }
}
