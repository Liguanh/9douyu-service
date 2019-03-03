<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/11/30
 * Time: 下午6:49
 */

namespace App\Services\Contract\JzqEbq;

use App\Services\Services;

/***** 引入文件 *****/
require_once(dirname(__FILE__).'/cfg/clientInfo.php') ;
require_once dirname(__FILE__).'/tool/shaUtils.php';
require_once dirname(__FILE__).'/tool/ropUtils.php';
require_once dirname(__FILE__).'/tool/httpSignUtils.php';
require_once dirname(__FILE__).'/model/enum.php';
require_once dirname(__FILE__).'/model/signatory.php';
require_once dirname(__FILE__).'/model/signStatusRequest.php';
require_once dirname(__FILE__).'/model/signNotifyRequest.php';
require_once dirname(__FILE__).'/model/signLinkRequest.php';
require_once dirname(__FILE__).'/model/presFileLinkRequest.php';
require_once dirname(__FILE__).'/model/pingRequest.php';
require_once dirname(__FILE__).'/model/uploadFile.php';
require_once dirname(__FILE__).'/model/fileLinkRequest.php';
require_once dirname(__FILE__).'/model/detailAnonyLinkRequest.php';
require_once dirname(__FILE__).'/model/certiAuthRequest.php';
require_once dirname(__FILE__).'/model/applySignFileRequest.php';
require_once dirname(__FILE__).'/model/organizationCreateRequest.php';
require_once dirname(__FILE__).'/model/organizationAuditStatusRequest.php';



use com_junziqian_api_tool\HttpSignUtils as HttpSignUtils;
use com_junziqian_api_tool\RopUtils as RopUtils;
use com_junziqian_api_tool\ShaUtils as ShaUtils;
use com_junziqian_api_cfg\ClientInfo as ClientInfo;
use com_junziqian_api_model\NeedVerifyPhone;
use com_junziqian_api_model\ServerCaAuto;
use com_junziqian_api_model\UploadFile as UploadFile;
use com_junziqian_api_model\ApplySignFileRequest as ApplySignFileRequest;
use com_junziqian_api_model\Signatory as Signatory;
use com_junziqian_api_model\IdentityType as IdentityType;
use com_junziqian_api_model\SignLevel as SignLevel;
use com_junziqian_api_model\DealType as DealType;
use com_junziqian_api_model\ServerCa as ServerCa;
use com_junziqian_api_model\DetailAnonyLinkRequest as DetailAnonyLinkRequest;
use com_junziqian_api_model\FileLinkRequest as FileLinkRequest;
use com_junziqian_api_model\PingRequest as PingRequest;
use com_junziqian_api_model\PresFileLinkRequest as PresFileLinkRequest;
use com_junziqian_api_model\SignLinkRequest as SignLinkRequest;
use com_junziqian_api_model\SignNotifyRequest as SignNotifyRequest;
use com_junziqian_api_model\SignStatusRequest as SignStatusRequest;

class JzqEbq
{
    protected $config;      //君子签的配置文件

    /**
     * JzqEdb constructor.
     * @param $config
     */
    public function __construct ($config)
    {
        $this->config   =   $config;

        new ClientInfo($config) ;
    }

    /**
     * @return string
     * @desc 主要用作服务通达性测试，类似于 ping 的功能，响应结果中无任何可用结果， 只作通达测试目的
     */
    public function ping()
    {
        $responseJson   =   RopUtils::doPostByObj ( new PingRequest() ) ;

        return json_decode( $responseJson, true ) ;
    }

    /**
     * @param array $data
     * @desc 上传文件到签约中心
     */
    public function doUpdateApplySignFile( $data = array() )
    {
        //组建请求参数
        $requestObj                 =   new ApplySignFileRequest() ;

        $filePath                   =   base_path () . '/contract_file/'. $data['file_number'] ;

        $this->getWriteFile ( $data['file_path'] , $filePath );

        $signatory                  =   new Signatory() ;
        //组建请求参数
        $requestObj->file           =   new UploadFile( $filePath ) ;

        //文件名称
        $requestObj->contractName   =   $data['contract_name']  ;
        //合同金额
        $requestObj->contractAmount =   $data['cash'];

        //签合同方
        $signatories=array();
        //姓名、身份证号、手机号不能部分或全部隐藏
        //使用身份证
        $signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
        //真实姓名
        $signatory->fullName        =   $data['real_name'] ;
        //身份证号码
        $signatory->identityCard    =   $data['identity_card'] ;
        //手机号码
        $signatory->mobile          =   $data['phone'] ;
        //GENERAL,标准图形章;SEAL，手写或公章
        $signatory->signLevel       =   SignLevel::$GENERAL;

        $signatory->noNeedVerify    =   NeedVerifyPhone::$NO_VERIFY ; //0：短信验证(默认) 1：不验证短信
        $signatory->orderNum        =   1 ;//签字顺序
        $signatory->serverCaAuto    =   ServerCaAuto::$AUTO_SIGN ;//0 手动签，1 自动签

        //[{"page":0,","chaptes":[{"offsetX":0.12,"offsetY":0.23}]},{"page":1,"chaptes":[{"offsetX":0.45,"offsetY":0.67}]}]
        //固定签章位置，以文件页左上角(0.0,0.0)为基准，按百分比进行设置）page为页码，从0开始计数，offsetX,offsetY(x,y为比例，值范围设置为0-1之间)  每页为一个数组，以此类推。

        $signatory->setChapteJson([
            [
                'page'      => isset($data['page']) && !empty($data['page']) ? $data['page'] : 0,
                'chaptes'   => isset($data['chaptes']) && !empty($data['chaptes']) ? $data['chaptes'] : [ ["offsetX"=>0.12,"offsetY"=>0.23] ] ,
            ]
        ]);

        array_push($signatories, $signatory);

        $requestObj->signatories=   $signatories;
        //1表示按顺序签（按signatories.orderNum顺序），默认不按顺序
        $requestObj->orderFlag  =   1 ;
        //使用云证书，0 不使用，1 使用
        $requestObj->serverCa   =   ServerCa::$USE_S_CA ;
        //签约类型,0或DEFAULT, "默认用户手动签字"；1或AUTH_SIGN, "自动签字并保全"；2或ONLY_PRES,"只做保全，用户不做签字"
        $requestObj->dealType   =   DealType::$AUTH_SIGN ;
        //请求
        $requestResponse=RopUtils::doPostByObj($requestObj) ;
        //以下为返回的一些处理
        return json_decode($requestResponse) ;
    }

    protected function getWriteFile( $filePath ,$writePath )
    {
        $pdfInfo    =   file_get_contents ($filePath);

        file_put_contents ( $writePath ,$pdfInfo );
    }
    /**
     * @param $data
     * @return mixed
     * @desc  获取签章的状态
     */
    public function getSignStatus( $data = array() )
    {
        $requestObj             =   new SignStatusRequest() ;

        $requestObj->applyNo    =   $data['apply_no'] ;

        $requestObj->signatory  =   $this->getSignatoryParam ( $data ) ;
        //请求
        $requestResponse        =   RopUtils::doPostByObj($requestObj) ;
        //以下为返回的一些处理
        return  json_decode($requestResponse) ;
    }

    /**
     * @param $data | array
     * @return mixed
     * @desc 获取签章处理结果，并进行数据回调
     */
    public function getSignNotify( $data = array() )
    {
        $requestObj             =   new SignNotifyRequest() ;

        $requestObj->applyNo    =   $data['apply_no'] ;

        $requestObj->signatory  =   $this->getSignatoryParam ($data ) ;
        //
        $requestObj->signNotifyType=SignNotifyRequest::$NOTIFYTYPE_SIGN ;

        $requestObj->backUrl    =   $data['back_url'] ;
        //请求
        $requestResponse        =   RopUtils::doPostByObj($requestObj) ;
        //以下为返回的一些处理
        return json_decode($requestResponse) ;
    }

    /**
     * @param $data | array
     * @return mixed
     * @desc 获取签约的地址
     */
    public function getSignLink( $data = array() )
    {
        $requestObj             =   new SignLinkRequest() ;

        $requestObj->applyNo    =   $data['apply_no'] ;

        $requestObj->signatory  =   $this->getSignatoryParam ( $data ) ;
        //请求
        $requestResponse        =   RopUtils::doPostByObj($requestObj) ;
        //以下为返回的一些处理
        return json_decode( $requestResponse ) ;
    }

    /**
     * @param $data | array
     * @return mixed
     * @desc 合同文件下载地址
     */
    public function getPresFileLine( $data = array() )
    {
        $requestObj             =   new PresFileLinkRequest() ;

        $requestObj->applyNo    =   $data['apply_no'] ;

        $requestObj->signatory  =   $this->getSignatoryParam ( $data )  ;
        //请求
        $requestResponse        =   RopUtils::doPostByObj($requestObj) ;
        //以下为返回的一些处理
        return json_decode( $requestResponse ) ;
    }


    /**
     * @param $data |array
     * @return mixed
     * @desc 获取合同地址
     */
    public function getFileLink( $data = array() )
    {
        $requestObj             =   new FileLinkRequest() ;

        $requestObj->applyNo    =   $data['apply_no'] ;
        //请求
        $requestResponse        =   RopUtils::doPostByObj($requestObj) ;
        //以下为返回的一些处理
        return json_decode($requestResponse) ;
    }


    /**
     * @param $data | array
     * @return mixed
     * @desc 在易保全电子数据保全中心获取签约详情查看链接
     */
    public function getDetailAnonymityLink( $data = [] )
    {
        $requestObj         =   new DetailAnonyLinkRequest() ;

        $requestObj->applyNo=   $data['apply_no'] ;
        //请求
        $requestResponse    =   RopUtils::doPostByObj($requestObj) ;
        //以下为返回的一些处理
        return json_decode($requestResponse) ;
    }

    /**
     * @param array $data
     * @return string
     * @desc 回调信息验证
     */
    public function returnInfo( $data =array() )
    {
        $result =   [
            "resultCode"    =>  "",
            "msg"           =>  "",
            "success"       =>  true,
        ] ;
        if( !isset($data['applyNo']) ) {
            $result['resultCode']   =  "jsonParamsError";
            $result['msg']          =  "applyNo is null";
            $result['success']      =  false;
            return json_encode($result,JSON_UNESCAPED_UNICODE) ;
        }
        if( !isset($data['identityType']) ) {
            $result['resultCode']   =   "jsonParamsError";
            $result['msg']          =   "identityType is null";
            $result['success']      =   false;
            return json_encode($result,JSON_UNESCAPED_UNICODE) ;
        }
        if( !isset($data['fullName']) ) {
            $result['resultCode']   =   "jsonParamsError";
            $result['msg']          =   "fullName is null";
            $result['success']      =   false;
            return json_encode($result,JSON_UNESCAPED_UNICODE) ;
        }
        if( !isset($data['identityCard']) ) {
            $result['resultCode']   =   "jsonParamsError";
            $result['msg']          =   "identityCard is null";
            $result['success']      =   false;
            return json_encode($result,JSON_UNESCAPED_UNICODE) ;
        }
        if( !isset($data['optTime']) ) {
            $result['resultCode']   =   "jsonParamsError";
            $result['msg']          =   "optTime is null";
            $result['success']      =   false;
            return json_encode($result,JSON_UNESCAPED_UNICODE) ;
        }
        if( !isset($data['signStatus']) ) {
            $result['resultCode']   =   "jsonParamsError";
            $result['msg']          =   "signStatus is null";
            $result['success']      =   false;
            return json_encode($result,JSON_UNESCAPED_UNICODE) ;
        }
        if( !isset($data['timestamp']) ) {
            $result['resultCode']   =   "jsonParamsError";
            $result['msg']          =   "timestamp is null";
            $result['success']      =   false;
            return json_encode($result,JSON_UNESCAPED_UNICODE) ;
        }
        if( !isset($data['sign']) ) {
            $result['resultCode']   =   "jsonParamsError";
            $result['msg']          =   "sign is null";
            $result['success']      =   false;
            return json_encode($result,JSON_UNESCAPED_UNICODE) ;
        }

        $bodyParams =   [
            'applyNo'       =>  $data['applyNo'],
            'identityType'  =>  $data['identityType'],
            'fullName'      =>  $data['fullName'],
            'identityCard'  =>  $data['identityCard'],
            'optTime'       =>  $data['optTime'],
            'signStatus'    =>  $data['signStatus'],    //签约状态	0未签、1已签、2拒签
            ] ;
        try {

            HttpSignUtils::checkHttpSign($bodyParams, $data['timestamp'], ClientInfo::$app_key, ClientInfo::$app_secret, $data['sign']) ;
        } catch ( \Exception $e) {
            $result['resultCode']   =   "signError";
            $result['msg']          =   $e->getMessage() ;
            $result['success']      =   false;
        }
        if( $result['success'] ) {
            //TODO 做自个的业务相关处理

        }
        return json_encode($result,JSON_UNESCAPED_UNICODE) ;
    }

    /**
     * @param $data
     * @return Signatory
     * @desc  个人参数调配
     */
    protected function getSignatoryParam( $data )
    {
        $signatory              =   new Signatory() ;

        $signatory->fullName    =   $data['real_name'] ;

        $signatory->identityCard=   $data['identity_card'] ;

        $signatory->setSignatoryIdentityType(IdentityType::$IDCARD) ;

        if( isset($data['mobile']) ) {

            $signatory->mobile  =   $data['phone'] ;
        }

        return $signatory;
    }

}
