<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/28
 * Time: 17:05
 */
namespace App\Http\Controllers\BankCard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Logics\BankCard\CardLogic;


class CardController extends Controller{


    private $logic = null;

    public function __construct(Request $request){

        parent::__construct($request);

        $this->logic = new CardLogic();
    }

    /**
     * @param Request $request
     * 融宝储蓄卡鉴权接口
     */
    public function checkDepositCard(Request $request){

        $cardNo     = $request->input('card_no','');//银行卡号
        $phone      = $request->input('phone','');//手机号
        $name       = $request->input('name',''); //姓名
        $idCard     = $request->input('id_card','');//身份证号

        $return = $this->logic->checkDepositCard($cardNo,$phone,$name,$idCard);

        self::returnJson($return);

    }


    /**
     * @param Request $request
     * 融宝信用卡鉴权接口
     */
    public function checkCreditCard(Request $request){

        $cardNo     = $request->input('card_no','');//银行卡号
        $name       = $request->input('name','');//姓名
        $idCard     = $request->input('id_card','');//身份证号
        $phone      = $request->input('phone','');//手机号
        $cvv2       = $request->input('cvv2','');//信用卡后三位校验码
        $validthru  = $request->input('validthru','');//信用卡用效期

        $return = $this->logic->checkCreditCard($cardNo,$phone,$name,$idCard,$cvv2,$validthru);

        self::returnJson($return);

    }


    /**
     * @param Request $request
     * 连连卡bin接口
     */
    public function getCardInfo(Request $request){

        $cardNo     = $request->input('card_no','');//银行卡号
        $return = $this->logic->getCardInfo($cardNo);
        self::returnJson($return);

    }
}