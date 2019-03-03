<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/10/27
 * Time: 18:18
 * @desc 系统配置的Logic层
 */

namespace App\Http\Logics\SystemConfig;

use App\Http\Dbs\SystemConfigDb;
use App\Http\Logics\Logic;
use GuzzleHttp\Psr7\Request;

class SystemConfigLogic extends Logic{

    private $db = null;

    public function __construct()
    {
        $this->db = new SystemConfigDb();
    }

    /**
     * @return array
     * @desc 获取配置的列表
     */
    public function getList(){

        $list = $this->db->getList();

        $list = ['total' => count($list), 'list' => $list];

        return self::callSuccess($list);
    }

    /**
     * @param $id
     * @return array
     * @desc 通过ID获取配置详情
     */
    public function getSystemConfigById($id){

        $info = $this->db->getInfoById($id);

        return self::callSuccess($info);
    }
    /**
     * @param $data
     * @return array
     * @desc 添加配置信息
     */
    public function addSysConfigInfo($data)
    {

        $res = $this->db->addInfo($data);

        if( $res ){

            return self::callSuccess($res);

        }else{

            return self::callError('创建失败');

        }

    }

    /**
     * @param $id
     * @param $data
     * @return array
     * @desc 更新配置记录
     */
    public function updateInfo($id, $data){

        if( !$id || empty($data) ){

            return self::callError('参数不正确');

        }

        $res = $this->db->updateInfo($id, $data);

        if( $res ){

            return self::callSuccess($res);

        }else{

            return self::callError('更新失败');

        }
    }

    /**
     * @param $key
     * @return bool|mixed
     * @desc 通过key值获取配置信息
     */
    public static function getConfigByKey($key){

        $keyValue = null;

        if(empty($key)){
            return false;
        }

        $keyArr = explode('.',$key);
        $key    = $keyArr[0];
        $systemConfigDb = new SystemConfigDb();
        $res = $systemConfigDb->getInfoByKey($key);

        if( !empty($res) ){

            $keyValue = unserialize($res['value']);

        }

        if(isset($keyArr[1])){

            return $keyValue[$keyArr[1]];

        }

        return $keyValue;
    }

}