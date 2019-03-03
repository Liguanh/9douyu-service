<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/28
 * Time: 17:12
 */
namespace App\Http\Logics\BankCard;

use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Pay\DriverModel;

class CardLogic extends Logic{


    /**
     * @param $phone
     * @param $name
     * @param $idCard
     * 储蓄卡鉴权
     */
    public function checkDepositCard($cardNo,$phone,$name,$idCard){

        try{
            //是否是合法的姓名
            ValidateModel::isName($name);
            //是否是合法的身份证号
            ValidateModel::isIdCard($idCard);
            //是否是合法的银行卡号
            ValidateModel::isBankCard($cardNo);

            //融宝验卡接口
            $model = DriverModel::getInstance('ReaWithholding');

            $result = $model->checkDepositCard($phone,$name,$idCard,$cardNo);

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess($result);

    }


    /**
     * @param $phone
     * @param $name
     * @param $idCard
     * @param $cvv2
     * @param $validthru
     * 信用卡鉴权接口
     */
    public function checkCreditCard($cardNo,$phone,$name,$idCard,$cvv2,$validthru){

        try{

            //验证卡号
            ValidateModel::isPhone($phone);
            //验证姓名
            ValidateModel::isName($name);
            //验证卡号
            ValidateModel::isBankCard($cardNo);
            //验证身份证号
            ValidateModel::isIdCard($idCard);

            //融宝验卡接口
            $model = DriverModel::getInstance('ReaWithholding');

            $result = $model->checkCreditCard($phone,$name,$idCard,$cardNo,$cvv2,$validthru);

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);

    }

    /**
     * 连连卡bin
     */
    public function getCardInfo($cardNo){

        try{
            ValidateModel::isBankCard($cardNo);

            //连连认证支付卡bin接口
            $model = DriverModel::getInstance('LLAuth');

            $result = $model->getCardInfo($cardNo);

        }catch(\Exception $e){
            return self::callError($e->getMessage());

        }
        return self::callSuccess($result);

    }
}