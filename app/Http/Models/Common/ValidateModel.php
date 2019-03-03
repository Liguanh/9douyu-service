<?php
/**
 * User: zhangshuang
 * Date: 16/4/19
 * Time: 10:39
 * Desc: 数据统一验证Model
 */
namespace App\Http\Models\Common;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Services\Sms\EmaySms;

class ValidateModel extends Model{


    //四种短信类型
    private static $smsType = [
        'Notice',
        'Market',
        'Verify',
        'Voice',
        'Flow' ,    //流量
        'Calls',    //话费
    ];

    //支付所有方法的汇总列表
    private static $payMethodList = [
        'encrypt',      //加密
        'decrypt',      //解密
        'submit',       //支付提交
        'search',       //主动查单
        'sendCode',     //发送验证码
        'signed',       //签约,
        'checkCard',    //验卡
        'unbind',       //解绑银行卡
    ];

    private static $contractDriverList = [
        'EBQ',      //易宝全合同接口
        'JZQ',      //易宝全君子签个人签章
    ];

    private static $contractMethodList = [
        'ping',                     //测试服务通用性
        'cfPreservationCreate',     //创建保全
        'cfDownloadUrl',            //获取合同保全文件下载地址
        'preservationGet',          //根据保全编号查询保全
        'certificateLinkGet',       //保全证书的证书链接
        'cfViewUrl',                //获取合同查看页URL
        'contractStatusGet',        //根据保全编号查询保全用户的确认状态
        'doUpdateApplySignFile',    //上传文件到签约中心--君子签
        'getSignStatus',            //获取签章的状态--君子签
        'getSignNotify',            //获取签章处理结果，并进行数据回调--君子签
        'getSignLink',              //获取签约的地址--君子签
//        'getPresFileLine',          //合同下载地址--君子签 通过合同编号，用户信息
//        'getFileLink',              //获取合同地址--君子签 通过合同编号
//        'getDetailAnonymityLink',    //在易保全电子数据保全中心获取签约详情查看链接
    ];


    //所有有效的支付通道
    private static $payDriverList = [
        'JdOnline',         //京东网银
        'ReaOnline',        //融宝网银
        'HnaOnline',        //新生网银
        'SumaOnline',       //丰付网银
        'LLAuth',           //连连认证
        'BFAuth',           //宝付认证
        'UCFAuth',          //先锋认证
        'SumaAuth',         //丰付认证
        'YeeAuth',          //易宝认证
        'QdbWithholding',      //钱袋宝代扣
        'UmpWithholding',      //联动优势代扣
        'BestWithholding',     //翼支付代扣
        'ReaWithholding',      //融宝代扣
    ];

    public static $codeArr = [
        'isEmpty'                           => 1,
        'isPartnerId'                       => 2,
        'isSign'                            => 3,
        'isPhone'                           => 4,
        'isVoiceMsg'                        => 5,
        'isType'                            => 6,
        'isMsg'                             => 7,
        'isEamil'                           => 8,
        'isTitle'                           => 9,
        'isSubject'                         => 10,
        'isMsgKey'                          => 11,
        'isPayDriver'                       => 12,
        'isMethod'                          => 13,
        'isOptions'                         => 14,
        'isData'                            => 15,
        'isTemplateId'                      => 16,
        'isToUser'                          => 17,
        'isName'                            => 18,
        'isBankCard'                        => 19,
        'isIdCard'                          => 20,
        'isContractMethod'                  => 21,
        'isContractDriver'                  => 22,
        'isPackPrice'                       => 23,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_COMMON_VALIDATE;



    /**
     * @param $userId
     * @throws Exception
     * 判断是否是正确的商户号
     */
    public static function isEmpty($params = []){

        if(empty($params)){

            throw new \Exception(LangModel::getLang('ERROR_PARAMS_IS_EMPTY'), self::getFinalCode('isEmpty'));
        }

    }

    /**
     * @param $userId
     * @throws Exception
     * 判断是否是正确的商户号
     */
    public static function isPartnerId($partnerId = 0){

        $pattern        = '/^\d{12}$/';
        if(!preg_match($pattern, $partnerId) || 0 === (int)$partnerId) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_PARTNER_ID'), self::getFinalCode('isPartnerId'));

        }

    }

    /**
     * @param $userId
     * @throws Exception
     * 判断是否是正确的签名
     */
    public static function isSign($sign = ''){

        $pattern        = '/^[0-9a-zA-Z]{32}$/';
        if(!preg_match($pattern, $sign) || '' === $sign) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_SIGN'), self::getFinalCode('isSign'));

        }

    }

    /**
     * @param string $phone
     * @throws \Exception
     * 判断是否是手效的手机号码
     */
    public static function isPhone($phone = ''){

        //$pattern    = '/^(13\d|14[57]|15[012356789]|18\d|17[01678])\d{8}$/';
        if('' === $phone) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_PHONE'), self::getFinalCode('isPhone'));

        }

    }

    /**
     * @param string $content
     * @throws \Exception
     * 验证短信是否内容是否合法
     */
    public static function isMsg($content = '')
    {

        $emaySms = new EmaySms();
        $black = $emaySms->checkMessageInBlacklist($content);

        if ('' === $content || $black === true) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_SMS_MSG'), self::getFinalCode('isMsg'));

        }

        //判断是否包含标识关键字
        if (stripos($content, '【九金服】') === false && stripos($content, '【九斗鱼】') === false && stripos($content, '回复TD退订') === false) {

            throw new \Exception(LangModel::getLang("ERROR_INVALID_SMS_KEYWORD"), self::getFinalCode('isMsgKey'));
        }

    }

    /**
     * @param string $method
     * @throws \Exception
     * 判断支付服务提供的方法是否存在
     */
    public static function isMethod($method = ''){

        if(!in_array($method,self::$payMethodList)){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_METHOD'), self::getFinalCode('isMethod'));
        }
    }

    /**
     * @param string $method
     * @throws \Exception
     * 判断合同服务提供的方法是否存在
     */
    public static function isContractMethod($method = ''){

        if(!in_array($method,self::$contractMethodList)){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_CONTRACT_METHOD'), self::getFinalCode('isContractMethod'));
        }
    }




    /**
     * @param string $type
     * @throws \Exception
     * 验证短信类型是否合法
     */
    public static function isType($type = ''){

        if($type === '' || !in_array($type,self::$smsType)){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_SMS_TYPE'), self::getFinalCode('isType'));

        }
    }


    /**
     * @param string $content
     * @throws \Exception
     * 验证语音验证码内容(4-6位数字)
     */
    public static function isVoiceMsg($content = ''){

        $pattern    = '/^\d{4,6}$/';

        if(!preg_match($pattern, $content) || '' === $content) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_VOICE_MSG'), self::getFinalCode('isVoiceMsg'));

        }

    }

    /**
     * @param string $email
     * @throws \Exception
     * 验证是否是有效的邮箱
     */
    public static function isEamil($email = ''){

        if ('' === $email) {
            throw new \Exception(LangModel::getLang("ERROR_INVALID_EMAIL"),self::getFinalCode("isEamil"));
        }

    }

    /**
     * @param string $method
     * @throws \Exception
     * 无效的支付通道
     */
    public static function isPayDriver($method = ''){

        if(!in_array($method,self::$payDriverList)){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_DRIVER'), self::getFinalCode('isPayDriver'));
        }
    }

    public static function isContractDriver($method = ''){

        if(!in_array($method,self::$contractDriverList)){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_CONTRACT_DRIVER'), self::getFinalCode('isContractDriver'));
        }

    }

    /**
     * @param string $title
     * @throws \Exception
     * 验证是否是有效的邮件标题
     */
    public static function isTitle($title = ''){

        if( '' === $title){

            throw new \Exception(LangModel::getLang("ERROR_INVALID_EMAIL_TITLE"),self::getFinalCode("isTitle"));

        }
    }

    /**
     * @param string $subject
     * @throws \Exception
     * 判断邮件内容是否为空
     */
    public static function isSubject($subject = ''){

        if('' === $subject){

            throw new \Exception(LangModel::getLang("ERROR_INVALID_EMAIL_SUBJECT"),self::getFinalCode("isSubject"));

        }
    }

    /**
     * @param string $options
     * @throws \Exception
     * 判断微信配置参数是否为空
     */
    public static function isOptions( $options = array()){

        if(empty($options)){
            throw new \Exception(LangModel::getLang("ERROR_INVALID_OPTIONS"),self::getFinalCode("isOptions"));
        }
    }

    /**
     * @param string $data
     * @throws \Exception
     * 判断模板消息参数是否为空
     */
    public static function isData( $data = array()){

        if(empty($data)){

            throw new \Exception(LangModel::getLang("ERROR_INVALID_DATA"),self::getFinalCode("isData"));
        }
    }

    /**
     * @param string $tempId
     * @throws \Exception
     * 判断模板ID是否为空
     */
    public static function isTemplateId( $tempId = ''){

        if( '' === $tempId){

            throw new \Exception(LangModel::getLang("ERROR_INVALID_TEMPLATE"),self::getFinalCode("isTemplateId"));
        }
    }

    /**
     * @param string $touser
     * @throws \Exception
     * 判断接收者的信息是否为空
     */
    public static function isTouser( $touser = ''){

        if( '' === $touser){

            throw new \Exception(LangModel::getLang("ERROR_INVALID_TOUSER"),self::getFinalCode("isToUser"));
        }
    }



    /**
     * 姓名格式校验
     * @param $name
     */
    public static function isName($name) {

        if(preg_match('#[a-z\d~!@\#$%^&*()_+{}|\[\]\-=:<>?/"\'\\\\]#', $name)) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_NAME'), self::getFinalCode('isName'));
        }
    }

    /**
     * @param string $cardNo
     * @throws \Exception
     * 判断银行卡号是否合法
     */
    public static function isBankCard($cardNo = ''){

        $len = strlen($cardNo);

        if(!($len == 16 || $len == 17 || $len == 19 || preg_match('#^\d{18}$#', $cardNo))){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_BANK_CARD'), self::getFinalCode('isBankCard'));
        }
    }

    /**
     * @param $idCard
     * @return bool
     * 身份证格式判断
     */
    public static function isIdCard($idCard)
    {

        if(!preg_match('/^(\d{15}|\d{17}X|\d{18})$/i', $idCard)) {
            $res = false;
        } else if(strlen($idCard) == 18) {
            $res     = self::idcard_checksum18($idCard);
        } else if((strlen($idCard) == 15)) {
            $idCard = self::idcard_15to18($idCard);
            $res     = self::idcard_checksum18($idCard);
        } else {
            $res     = false;
        }

        if(empty($res)) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_ID_CARD'), self::getFinalCode('isIdCard'));
        }
        return true;
    }



    // 计算身份证校验码，根据国家标准GB 11643-1999
    private static function idcard_verify_number($idcard_base)
    {
        if(strlen($idcard_base) != 17) {
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++) {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }

    // 将15位身份证升级到18位
    private static function idcard_15to18($idcard){
        if (strlen($idcard) != 15){
            return false;
        } else {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
                $idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
            } else {
                $idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
            }
        }
        $idcard = $idcard . self::idcard_verify_number($idcard);
        return $idcard;
    }

    // 18位身份证校验码有效性检查
    private static function idcard_checksum18($idcard){
        if (strlen($idcard) != 18){
            return false;
        }
        $idcard_base = substr($idcard, 0, 17);
        if (self::idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @param $packPrice
     * @return bool
     * @throws \Exception
     * @desc 是否为数字
     */
    public static function isPackPrice($packPrice)
    {
        if( empty($packPrice) ||  !is_numeric($packPrice ) ) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_PACK_PRICE'), self::getFinalCode('isPackPrice'));
        }

        return true;
    }

}
