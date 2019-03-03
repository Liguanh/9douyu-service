<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/11/30
 * Time: 下午6:49
 */

namespace App\Services\Contract\EBaoQuan;

use App\Services\Services;

require_once dirname(__FILE__).'/cfg/clientInfo.php';
require_once dirname(__FILE__).'/tool/ropUtils.php';
require_once dirname(__FILE__).'/tool/httpSignUtils.php';
require_once dirname(__FILE__).'/model/pingRequest.php';
require_once dirname(__FILE__).'/model/preservationGetRequest.php';
require_once dirname(__FILE__).'/model/certificateLinkGetRequest.php';
require_once dirname(__FILE__).'/model/contractFileDownloadUrlRequest.php';
require_once dirname(__FILE__).'/model/contractFilePreservationCreateRequest.php';
require_once dirname(__FILE__).'/model/uploadFile.php';
require_once dirname(__FILE__).'/model/enum.php';
require_once dirname(__FILE__).'/model/contractFileViewUrlRequest.php';
require_once dirname(__FILE__).'/model/contractStatusGetRequest.php';

use org_mapu_themis_rop_cfg\ClientInfo;
use org_mapu_themis_rop_tool\RopUtils as RopUtils;
use org_mapu_themis_rop_model\PingRequest as PingRequest;
use org_mapu_themis_rop_model\PreservationGetRequest as PreservationGetRequest;
use org_mapu_themis_rop_model\CertificateLinkGetRequest as CertificateLinkGetRequest;
use org_mapu_themis_rop_model\ContractFileDownloadUrlRequest as ContractFileDownloadUrlRequest;
use org_mapu_themis_rop_model\ContractFilePreservationCreateRequest as ContractFilePreservationCreateRequest;
use org_mapu_themis_rop_model\UploadFile as UploadFile;
use org_mapu_themis_rop_model\PreservationType as PreservationType;
use org_mapu_themis_rop_model\UserIdentiferType as UserIdentiferType;
use org_mapu_themis_rop_model\ContractFileViewUrlRequest as ContractFileViewUrlRequest;
use org_mapu_themis_rop_model\ContractStatusGetRequest as ContractStatusGetRequest;
use org_mapu_themis_rop_tool\HttpSignUtils as HttpSignUtils;


class EBaoQuan
{

    protected $config;
    protected $contractFilePath;

    public function __construct($config)
    {
        $this->config = $config;
        new ClientInfo($config);
    }

    /**
     * @return mixed
     * @throws \Exception
     * ping 服务
     * 测试服务可用性：
     * 主要用作服务通达性测试，类似于ping的功能，响应结果中无任何可用结果，只作通达测试目的。
     */
    public function ping()
    {

        $requestObj=new PingRequest();

        $response=RopUtils::doPostByObj($requestObj);

        //以下为返回的一些处理
        $responseJson=json_decode($response);

        return $responseJson;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * 创建保全
     */
    public function contractFilePreservationCreate( $data )
    {
//        $contractFilePath = dirname(__FILE__).'/contract/';

//        $filePath                       = $contractFilePath.$data['file_path'];
        $filePath                       = $data['file_path'];

        //下面语句当系统为windows时，访问中文文件名的内容需转换，当前程序中的文件名从utf-8->gbk，再进行读取文件路径
//        $fileName                       = RopUtils::getFileName($filePath);
        $fileName                       = $data['file_name'];

        //$filePath =iconv("utf-8","gb2312//IGNORE", $filePath);

        //封装上传文件内容
        $file                           = new UploadFile();
        $file->content                  = file_get_contents($filePath);
        $file->fileName                 = $fileName;

        //初始化合同文件上传
        $requestObj                     = new ContractFilePreservationCreateRequest();
        //init保全请求参数
        $requestObj->contractNumber     = $data['contract_num']; //合同编号
        $requestObj->contractAmount     = $data['cash'];         //合同金额

        //通用参数
        $requestObj->sourceRegistryId   = $data['user_id'];     //用户ID，客户自定义
        $requestObj->userIdentifer      = $data['identity'];    //用户身份标识信息
        $requestObj->userRealName       = $data['real_name'];   //真实姓名
        $requestObj->userIdentiferType  = UserIdentiferType::$PRIVATE_ID; //0 或者 1  (0, 个人身份证 ; 1 企业营业执照)
//        $requestObj->userEmail          = !empty($data['email'])?$data['email']:'';   //email
//        $requestObj->mobilePhone        = !empty($data['phone'])?$data['phone']:'';   //手机号
        $requestObj->userEmail          = '';   //email
        $requestObj->mobilePhone        = '';   //手机号

        $requestObj->file               = $file;

        $requestObj->preservationTitle  = $fileName;  //保全标题
        $requestObj->preservationType   = PreservationType::$DIGITAL_CONTRACT; //保全类型

        //$requestObj->objectId         = "0000001";//关联保全时使用
        $requestObj->comments           = !empty($data['desc'])?$data['desc']:'';
        $requestObj->isNeedSign         = 1;

        //isNeedSign 这个参数如果为1是需要签名，则上传的文件必须是pdf文件，且服务端会将文件做签名后再保全.请->
        //使用保全contractFileDownloadUrl.php例子的使用方法得到合同保全的保全后文件的下载地址进行下载。（下载地址有时效性，过期后重新按此方法取得新地址）

        //请求服务器
        $response=RopUtils::doPostByObj($requestObj);
        //以下为返回的一些处理
        $responseJson=json_decode($response);

        return $responseJson;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 获取合同保全文件下载地址
     */
    public function contractFileDownloadUrl( $data )
    {

        //组建请求参数
        $requestObj=new ContractFileDownloadUrlRequest();
        //init params
        $requestObj->preservationId = $data['preservation_id'];
        //请求
        $response=RopUtils::doPostByObj($requestObj);

        //以下为返回的一些处理
        $responseJson=json_decode($response);

        return $responseJson;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * 根据保全编号查询保全
     */
    public function preservationGet( $data ){

        //组建请求参数
        $requestObj=new PreservationGetRequest();
        $requestObj->preservationId = $data['preservation_id'];
        //请求
        $response=RopUtils::doPostByObj($requestObj);

        //以下为返回的一些处理
        $responseJson=json_decode($response);

        return $responseJson;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * 保全证书的证书链接
     */
    public function certificateLinkGet( $data )
    {

        //组建请求参数
        $requestObj=new CertificateLinkGetRequest();
        $requestObj->preservationId = $data['preservation_id'];

        //请求
        $response=RopUtils::doPostByObj($requestObj);

        //以下为返回的一些处理
        $responseJson=json_decode($response);

        return $responseJson;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * 获取合同查看页URL
     */
    public function contractFileViewUrl( $data )
    {
        //组建请求参数
        $requestObj=new ContractFileViewUrlRequest();

        //init params
        $requestObj->preservationId = $data['preservation_id'];

        //请求
        $response=RopUtils::doPostByObj($requestObj);

        //以下为返回的一些处理
        $responseJson=json_decode($response);

        return $responseJson;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * 根据保全编号查询保全用户的确认状态
     */
    public function contractStatusGet( $data ){

        //组建请求参数
        $requestObj=new ContractStatusGetRequest();
        //init params
        $requestObj->preservationId = $data['preservation_id'];
        //请求
        $response=RopUtils::doPostByObj($requestObj);

        //以下为返回的一些处理
        $responseJson=json_decode($response);

        return $responseJson;

    }

    /**
     * @param $data
     * @return string
     * @throws \Exception
     * 回调验证
     */
    public function returnInfo( $data ){

        $result=array(
            "resultCode"    => "",
            "msg"           => "",
            "success"       => true,
        );

        if( !isset($data['preservationId']) )
        {
            $result['resultCode']   = "jsonParamsError";
            $result['msg']          = "preservationId is null";
            $result['success']      = false;
            return json_encode($result,JSON_UNESCAPED_UNICODE);
        }

        if( !isset($data['checkTime']) )
        {
            $result['resultCode']   = "jsonParamsError";
            $result['msg']          = "checkTime is null";
            $result['success']      = false;
            return json_encode($result,JSON_UNESCAPED_UNICODE);
        }

        if( !isset($data['timestamp']) )
        {
            $result['resultCode']   = "jsonParamsError";
            $result['msg']          = "timestamp is null";
            $result['success']      = false;
            return json_encode($result,JSON_UNESCAPED_UNICODE);
        }

        if( !isset($data['sign']) )
        {
            $result['resultCode']   = "jsonParamsError";
            $result['msg']          = "sign is null";
            $result['success']      = false;
            return json_encode($result,JSON_UNESCAPED_UNICODE);
        }

        $preservationId = $data['preservationId'];
        $checkTime      = $data['checkTime'];
        $timestamp      = $data['timestamp'];
        $sign           = $data['sign'];
        $bodyParams=array(
            'preservationId'    => $preservationId,
            'checkTime'         => $checkTime
        );

        try {

            HttpSignUtils::checkHttpSign($bodyParams, $timestamp, ClientInfo::$app_key, ClientInfo::$app_secret, $sign);

        } catch (Exception $e) {

            $result['resultCode']="signError";
            $result['msg']=$e->getMessage();
            $result['success']=false;

        }

        if($result['success']){
            //TODO 做自个的业务相关处理

        }

        return json_encode($result,JSON_UNESCAPED_UNICODE);

    }

}