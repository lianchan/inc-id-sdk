<?php
/**
 * Created by PhpStorm.
 * User: chenjianhua
 * Date: 2018/5/16
 * Time: 下午5:38
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

include '../../../autoload.php';
//加载 vendor/autoload.php
use Incidsdk\Src\Lib\ILogin;

$au = new ILogin([]);
$au->authorizeUrl();