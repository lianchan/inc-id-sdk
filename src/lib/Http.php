<?php

/**
 * Created by PhpStorm.
 * User: chenjianhua
 * Date: 2018/6/14
 * Time: 下午2:29
 */

namespace Incidsdk\Src\Lib;
Class Http
{
    public static function __request($url, $params = [], $is_post = false, $time_out = 30, $header = []):string
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置是否返回response header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);//当需要通过curl_getinfo来获取发出请求的header信息时，该选项需要设置为true
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $time_out);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        if ($is_post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $response = curl_exec($ch);
        $request_header = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        //print_r($request_header);//打印请求的header信息
        curl_close($ch);
        return $response;
    }
}