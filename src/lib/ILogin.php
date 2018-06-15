<?php
/*
 * @class IncidLogin
 * @auth chenjianhua dyoungchen@gmail.com $
 * @date 2018年06月13日11:51:28
 */

namespace Incidsdk\Src\Lib;

use const JSON_UNESCAPED_UNICODE;
use function dirname;
use function file_get_contents;
use function file_put_contents;
use function header;
use function json_decode;
use function json_encode;
use function time;
use function uniqid;
use function var_dump;

Class ILogin
{
    /*
     * 测试开发者账号
     * 账号: dev_test@test.com.cn
     * 密码: inctest666
     * 测试 APPID:
     * 测试 APPSECRET:
     */

    private $appId = '296889374648303616';//测试使用账号
    private $appSecret = 'BveaCUIb38P06yZWszugZnhcAR7BuUjC';//测试使用 app secret
    public $scope = 'snsapi_base';
    public $goto = 'aHR0cCUzQS8vMTI3LjAuMC4xJTNBOTAwNS91c2VyaW5mby5waHA=';//base64加密的字符串,用于前往业务域名下的具体地址
    private $env = '';
    private $config = [];

    public function __construct($config = [])
    {
        if (empty($config)) {
            $config2 = \Incidsdk\Src\Lib\Config::Get();
            $this->config = $config2;
        }
        $this->setEnv($env = 'env_test');
        if (isset($config['appIdd']))
            $this->appId = $config['appId'];
        if (isset($config['appSecret']))
            $this->appSecret = $config['appSecret'];
        if (isset($config['scope']))
            $this->scope = $config['scope'];
    }

    public function setEnv($env = '')
    {
        $this->env = $env;
    }

    /*
     * 生成授权地址
     * @return html String
     */
    public function authorizeUrl()
    {
//        if(empty($this->env)){
//            exit('env must be set!');
//        }
        $state = uniqid() . time();
        //$authorizeUrl = 'http://106.14.114.102:5057/#/login?appid=296889374648303616&goto=aHR0cCUzQS8vMTI3LjAuMC4xJTNBOTAwNS91c2VyaW5mby5waHA=&response_type=CODE&scope=snsapi_base&state=STATE&grant_type=authorization_code';
        //https://www.incid.org
        $authorizeUrl = $this->config[$this->env]['page'] . '/#/login?'
            . 'appid=' . $this->appId .
            '&goto=' . $this->goto .
            '&response_type=CODE' .
            '&scope=' . $this->scope .
            '&state=' . $state .
            '&grant_type=authorization_code';
        header("Location:$authorizeUrl");
    }

    public function authCallBack()
    {
        $code = $_REQUEST['code'];
        if (!$code) {
            $logs = date('Y-m-d H:i:s') . ' code 为空,授权失败,请重新授权' . PHP_EOL;
            Logs::write_logs($logs);
            echo 'code 为空,授权失败,请重新授权';
        }
        //请求 access_token
        //判断缓存的 access_token
        $oldAccessToken = json_decode(file_get_contents(LIBDIR . '/data/access_token.json'), true);
        if (!isset($oldAccessToken['refresh_token'])) {
            //第一次缓存
            $tokenArr = $this->getAccessToken($code);
            $access_token = $tokenArr['access_token'];
            $open_uid = $tokenArr['open_uid'];
        } else {
            //判断时间是否失效
            //失效需要重新获取 access_token
            if (isset($oldAccessToken['refresh_token']) && (time() > ($oldAccessToken['expire_in'] + $oldAccessToken['create_time']))) {
                //过期更新
                //使用 refresh_token 获取 access_token
                $tokenArr = $this->refreshToken($oldAccessToken['refresh_token']);
                $access_token = $tokenArr['access_token'];
                $open_uid = $tokenArr['open_uid'];

            } else {
                $access_token = $oldAccessToken['access_token'];
                $open_uid = $oldAccessToken['open_uid'];

            }
        }

        //获取用户信息
        if ($access_token) {
            echo '授权成功, access_token 为 ' . $access_token . '<br/>';
        }
        echo 'open_uid ' . $open_uid . 'access_token ' . $access_token . '<br/>';
        $userInfo = $this->getUserInfo($access_token, $open_uid);
        echo '<br/>';
        //var_dump($userInfo);
        if ($userInfo) {
            //这里是你的逻辑代码
            echo '获取用户信息成功!!';
            var_dump($userInfo);
        }

    }

    /*
     * 根据code获取授权access_toke
     * @param  $parameters
     */
    public function getAccessToken($code = '')
    {
        //https://auth.incid.org
        $tokenUrl = $this->config[$this->env]['api'] . '/token?' .
            'appid=' . $this->appId .
            '&secret=' . $this->appSecret .
            '&grant_type=authorization_code' .
            '&code=' . $code .
            '&scope=' . $this->scope;
        $reqResult = \Incidsdk\Src\Lib\Http::__request($tokenUrl, [], false, 30, []);
        $tokenArr = json_decode($reqResult, true);
        if (40064 == $tokenArr['status']) {
            //重新授权
            $this->refreshToken();
        }
        //var_dump($tokenArr,'access_token first');
        $tokenArr['create_time'] = (time() + 5);
        file_put_contents(LIBDIR . '/data/access_token.json', json_encode($tokenArr, JSON_UNESCAPED_UNICODE));
        return $tokenArr;
    }


    /*
     * 根据code获取授权access_toke
     * @param  $parameters
     */
    public function refreshToken($refreshToken = '')
    {
        $refreshTokenUrl = $this->config[$this->env]['api'] . '/token?' .
            'appid=' . $this->appId .
            '&secret=' . $this->appSecret .
            '&grant_type=refresh_token' .
            '&code=CODE' .
            '&mode=authorization_code' .
            '&scope=' . $this->scope .
            '&refresh_token=' . $refreshToken;
        $reqResult = \Incidsdk\Src\Lib\Http::__request($refreshTokenUrl, [], false, 30, []);
        $tokenArr = json_decode($reqResult, true);
        //如果 refresh_token 也失效,重新授权
        if (400519 == $tokenArr['status']) {
            //重新授权
            $this->authorizeUrl();
        }
        //var_dump($tokenArr,'refresh token first');
        $tokenArr['create_time'] = (time() + 5);
        file_put_contents(LIBDIR . '/data/access_token.json', json_encode($tokenArr, JSON_UNESCAPED_UNICODE));
        return $tokenArr;
    }

    /*
     * 根据access_token以及open_uid获取用户信息
     * @param  $access_token
     * @param  $open_uid
     */
    public function getUserInfo($access_token, $open_uid)
    {
        $infoUrl = $this->config[$this->env]['api'] . '/open/user/info_by_openuid?' .
            'access_token=' . $access_token .
            '&open_uid=' . $open_uid;
        //var_dump($infoUrl);exit;
        $reqResult = \Incidsdk\Src\Lib\Http::__request($infoUrl, [], false, 30, []);
        return json_decode($reqResult, true);
    }


    /*
     * @param null
     * @return mixed html content
     */
    public function idVerify()
    {
        $url = $this->config[$this->env]['page'] . '/#/home';
        header("Location:$url");
    }
}