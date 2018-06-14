<?php
/**
 * Created by PhpStorm.
 * User: chenjianhua
 * Date: 2018/5/16
 * Time: 下午5:38
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
use incid_sdk\src\lib\autoloader;

include '../src/lib/autoloader.php';
autoloader::register();
$incLogin = new incid_sdk\src\lib\ILogin($config);

$incLogin->authorizeUrl();