<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$app->get('/', function () use ($app) {

	return 'Welcome ';
	//return $app->version();
});

$app->post('getCardInfo','BankCard\CardController@getCardInfo');
$app->get('swagger/config', 'Swagger\ApiController@config');
$app->get('swagger/index', 'Swagger\ApiController@index');

$app->post('oauth/access_token', function() {
    return response()->json(app('oauth2-server.authorizer')->issueAccessToken());
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace'=>'App\\Http\\Controllers', 'middleware' => 'api.auth'], function ($api) use ($app){
    $api->get('stats', function(){
        return [
            'stats' => 'dingoapi is ok'
        ];
    });

    $api->get('users/~me', function(){
        $user = app('Dingo\Api\Auth\Auth')->user();
        return $user;
    });

    $api->post('recharge/index','Pay\RechargeController@index');

	$api->post('getSign','Controller@getSign');
	//通知类短信
	$api->post("notice",'Sms\SmsController@sendNotice');
	//营销短信
	$api->post("market",'Sms\SmsController@sendMarket');
	//验证码短信
	$api->post("verify",'Sms\SmsController@sendVerify');
	//语音短信
	$api->post("voice",'Sms\SmsController@sendVoice');
    //获取短信内容验证黑名单
	$api->post("blackList",'Sms\SmsController@getBlackList');
	//发送内容为字符型的邮件
	$api->post("sendMail",'Email\EmailController@sendMail');
	//发送内容带HTML格式的邮件
	$api->post("sendMailHtml",'Email\EmailController@sendMailHtml');

	//发送微信模板消息
	$api->post('wxMsg','Push\WeiXinController@sendTemplateMessage');


	$api->post('recharge','Pay\RechargeController@index');

	//储蓄卡鉴权接口
	$api->post('checkDepositCard','BankCard\CardController@checkDepositCard');

	//信用卡鉴权接口
	$api->post('checkCreditCard','BankCard\CardController@checkCreditCard');

	$api->post('getCardInfo','BankCard\CardController@getCardInfo');

	//系统配置
	$api->post('SystemConfig/list', 'SystemConfig\SystemConfigController@getList');//配置列表
	$api->post('SystemConfig/addConfig', 'SystemConfig\SystemConfigController@addSystemConfigInfo');//添加配置
	$api->post('SystemConfig/editConfig', 'SystemConfig\SystemConfigController@updateSystemConfigInfo');//编辑配置
	$api->post('SystemConfig/getConfigById', 'SystemConfig\SystemConfigController@getInfoById');//通过ID获取信息

    $api->post("send/flow",'Sms\FlowController@sendFlow');       //充值流量
    $api->post("send/calls",'Sms\FlowController@sendCalls'); //充值话费

	//合同
	$api->post('contract/index','Contract\ContractController@index');

});

//微信模板消息测试
//$app->post('tempMsg', 'Test\WeiXinController@sendTempMsg');


