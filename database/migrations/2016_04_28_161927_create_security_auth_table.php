<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityAuthTable extends Migration
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
        Schema::create('security_auth', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('安全验证配置表');
            $table->string('partner_id',20)->default('')->comment('商户ID');
            $table->string('secret_key',32)->default('')->comment('秘钥');
            $table->string('partner_name',200)->default('')->comment('商户名称');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('状态，1-启用，0-禁用');
            $table->timestamps();
            $table->index(['partner_id','status']);
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
