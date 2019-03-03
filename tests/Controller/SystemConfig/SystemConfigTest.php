<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/10/27
 * Time: 18:36
 */

class SystemConfigTest extends TestCase{

    private $logic = null;

    public function __construct()
    {
        $this->logic = new \App\Http\Logics\SystemConfig\SystemConfigLogic();
    }

    /**
     * @desc 获取列表
     */
    public function testGetList(){


        $res = $this->logic->getList();


        var_dump($res);
    }

    /**
     * 添加记录
     */
    /*public function testAddInfo(){

        $data = [
            'name'          => "服务端短信通道设置",
            'key'           => "SMS_CHANNEL_SERVICE",
            'value'         => serialize(["NOTICE" => "JzSms","VERIFY" => "JzSms", "MARKET" => "MdSms"]),
            'second_des'    => serialize(["NOTICE" => "通知类","VERIFY" => "验证类短信", "MARKET" => "营销类"])?:'',
            'user_id'       =>  1, //todo lqh
            'status'        =>  1,
            'config_type'   => 'service'

        ];

        $res  = $this->logic->addSysConfigInfo($data);

        var_dump($res);
    }*/
    /**
     * @desc 更新配置记录
     */

    public function testEditInfo(){

        $id = 1;

        $data = [
            'name'          => "服务端短信通道设置",
            'key'           => "SMS_CHANNEL_SERVICE",
            'value'         => serialize(["NOTICE" => "JzSms","VERIFY" => "JzSms", "MARKET" => "MdSms"]),
            'second_des'    => serialize(["NOTICE" => "通知类","VERIFY" => "验证类短信", "MARKET" => "营销类"])?:'',
            'user_id'       =>  1, //todo lqh
            'status'        =>  1,
            'config_type'   => 'service'

        ];

        $res = $this->logic->updateInfo($id, $data);

        var_dump($res);
    }

    /**
     * @desc 通过ID获取配置信息
     */
    public function testGetInfoById(){
        $id = 2;

        $res = $this->logic->getSystemConfigById($id);

        var_dump($res);
    }

    /**
     * @desc 通过key值获取配置信息
     */
    public function testGetInfoByKey(){

        $key = "SMS_CHANNEL_SERVICE";

        $res  =  \App\Http\Logics\SystemConfig\SystemConfigLogic::getConfigByKey($key);

        var_dump($res);
    }

}