<?php
/**
 * Created by PhpStorm.
 * User: chenjianhua
 * Date: 2018/5/16
 * Time: 下午5:38
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include '../src/lib/autoloader.php';
//加载 test autoload/autoload.php
use Incidsdk\Src\Lib\autoloader;
use Incidsdk\Src\Lib\ILogin;

autoloader::register();

$au = new ILogin([]);
$au->authCallBack();