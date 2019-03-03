<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/11/30
 * Time: 下午5:28
 */
return [
    //易宝全
    'E_BAO_QUAN' => [

        /*
         * 沙箱和正式环境的app_key、app_secret、services_url均不同
         * 前期测试请联系技术支持申请沙箱的：app_key、app_secret
         * 生产上不要用于测试，请使用正规内容进行保全api操作。
         *
         * 测试
         * APP_KEY：f3d0e4ed45c64576
         * APP_SECRET：506ea6bcc0bee72fb6a8512b71db97ca
         * SERVICE_URL：http://sandbox.api.ebaoquan.org/services
         *
         * 正式
         * APP_KEY：1f1c2ee9be6eb41c
         * APP_SECRET：95ffc845dd673f91e393a342ae4d2e8f
         * SERVICE_URL：http://api.ebaoquan.org/services
         *
         * */

        //服务商服务地址
        'services_url' => 'http://api.ebaoquan.org/services',
        //app_key对应从服务商申请到的appkey
        'app_key' => '1f1c2ee9be6eb41c',
        //appkey对应的密钥,客户使用,不能公开
        'app_secret' => '95ffc845dd673f91e393a342ae4d2e8f',

    ],
    //君子签
    'JZQ_CONFIG' => [
        /*
         * 沙箱和正式环境的app_key、app_secret、services_url均不同
         * 前期测试请联系技术支持申请沙箱的：app_key、app_secret
         * 生产上不要用于测试，请使用正规内容进行个人签章api操作。
         *
         * 测试
         * APP_KEY：9aaf93f90c74d339
         * APP_SECRET：055108bb9aaf93f90c74d3394e414f1a
         * SERVICE_URL：http://sandbox.api.junziqian.com/services
         *
         * 正式
         * APP_KEY：7508a8f1cfe2fcc3
         * APP_SECRET：600aac2e7508a8f1cfe2fcc3c5bf73c4
         * SERVICE_URL：http://api.junziqian.com/services
         *
         * */

        //app_key对应从服务商申请到的appkey
        //'app_key'       => '9aaf93f90c74d339',
        'app_key'       => '7508a8f1cfe2fcc3',
        //appkey对应的密钥,客户使用,不能公开
        //'app_secret'    => '055108bb9aaf93f90c74d3394e414f1a',
        'app_secret'    => '600aac2e7508a8f1cfe2fcc3c5bf73c4',
        //服务商服务地址
        //'services_url'  => 'http://sandbox.api.junziqian.com/services',
        'services_url'  => 'http://api.junziqian.com/services',
    ],
];
