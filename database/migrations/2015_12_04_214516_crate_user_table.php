<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nick_name', 60);// 用户昵称
            $table->char('mobile', 11);// 手机号码
            $table->integer('price');// 产品价格
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
        Schema::connection('mysql2')->drop('user');
    }
}
