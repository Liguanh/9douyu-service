<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckCardLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        //提现银行卡数据表
        Schema::create('check_card_log', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('储蓄卡四要素验卡日志表');
            $table->string('partner_id',20)->default('')->comment('商户ID');
            $table->text('request')->default('')->comment('请求参数');
            $table->text('response')->default('')->comment('响应结果');
            $table->smallInteger('status')->unsigned()->default(200)->comment('状态，200-成功，500-失败');
            $table->timestamps();
            $table->index(['partner_id','created_at']);
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
