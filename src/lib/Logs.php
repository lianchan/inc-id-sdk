<?php
/**
 * Created by PhpStorm.
 * User: chenjianhua
 * Date: 2018/6/13
 * Time: 上午11:56
 */

namespace Incidsdk\Src\Lib;

use const FILE_APPEND;
use function file_put_contents;
use const LIBDIR;

class Logs
{
    public static function write_logs($logs, $file_name = '')
    {
        if (!$file_name) {
            $file_name = LIBDIR.'/data/logs/' . date('Y-m-d') . 'logs.log';
        }
        file_put_contents($file_name, $logs, FILE_APPEND);
    }
}