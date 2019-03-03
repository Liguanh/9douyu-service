<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 11:48
 */
namespace App\Http\Dbs;
use App\Http\Dbs\JdyDb;

class SecurityAuthDb extends JdyDb{

    const   STATUS_NORMAL = 200,    //正常
            STATUS_LOCKED = 500;    //关闭锁定

    /**
     * @param $partnerId
     * 根据商户ID获取对应的信息
     */
    public function getInfoByPartnerId($partnerId){

        return self::where('partner_id',$partnerId)->first();
    }
}