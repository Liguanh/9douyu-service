<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 11:35
 */
namespace App\Http\Logics\Auth;
use App\Http\Logics\Logic;
use App\Http\Dbs\SecurityAuthDb;
use App\Http\Models\Common\ValidateModel;

class SecurityAuthLogic extends Logic{


    /**
     * @param $partnerId
     * @param $sign
     * @param string $data
     * @return bool|mixed
     * 根据partnerID获取对应的数据并判断签名
     */
    public static function checkSignByPartnerId($partnerId, $sign, $data='')
    {

        $info = self::getPartnerInfo($partnerId);

        if( $info['status'] === SecurityAuthDb::STATUS_LOCKED ){
            //状态异常
            return false;

        }


        $md5Sign = self::createSign($info['secret_key'],$data);
        if( $md5Sign === $sign ){

            return $info;
        }

        return false;

    }

    /**
     * @param $partnerId
     * @return mixed
     * 根据商户号获取对应的商户信息
     */
    public static function getPartnerInfo($partnerId){

        //通过商户号查询相应的信息
        $db = new SecurityAuthDb();
        $info = $db->getInfoByPartnerId($partnerId);

        return $info;
        
    }

    /**
     * @param $params
     * @param $key
     * @return string
     * 生成签名
     */
    public static function createSign($secretKey,$params){

        unset($params['partner_id'],$params['secret_sign']);

        ksort($params);   //排序关联数组

        $data = json_encode($params);
        $sign = md5(md5($data).$secretKey);
        
        return $sign;
    }
}