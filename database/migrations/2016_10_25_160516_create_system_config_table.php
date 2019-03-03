<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //系统配置信息
        Schema::create('system_config', function (Blueprint $table) {
            $table->increments('id')->comment('系统配置');
            $table->char('name',50)->comment('键名描述');
            $table->char('key',50)->unique('key','index_key')->comment('键名');
            $table->text('value')->comment('键值');
            $table->integer('user_id')->comment('操作人ID');
            $table->tinyInteger('status')->comment('状态，0：未启用；1：启用');
            $table->text('second_des')->comment('二级参数的描述');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
