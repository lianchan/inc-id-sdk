<?php
/**
 * Created by PhpStorm.
 * User: chenjianhua
 * Date: 2018/6/13
 * Time: 下午2:52
 */
return [
    'sdk_language'    =>    'PHP',//sdk 语言 PHP
    'sdk_version'     =>    'v1.1.2018.06',//sdk 版本
    'sdk_auth'        =>    'chenjianhua',//sdk 作者
    'sdk_auth_email'  =>    'dyoungchen@gmail.com',//sdk 作者邮箱
    'appId'           =>    '296889374648303616',//开发者获取的 appid
    'appSecret'          =>    'BveaCUIb38P06yZWszugZnhcAR7BuUjC', //开发者获获取的 appsecret
    'scope'          =>    'snsapi_base', //开发者获获取的 appsecret
    'env_test' =>[
        'api'=>'http://106.14.114.102:5059',
        'page'=>'http://106.14.114.102:5057'
    ],//开发环境,不要随便修改
    'env_product' =>[
        'api'=>'https://auth.incid.org',
        'page'=>'https://www.incid.org'
    ]//正式环境接口,不要随便修改
];