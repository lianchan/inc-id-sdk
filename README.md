>### `  InfluenceChain INC OPEN 授权登录  PHP_SDK`

官方网站[InfluenceChain][1]

[1]: http://www.influencechain.org/        "InfluenceChain" 
##
#技术文档
<pre style="color:red;">
INCID 用户授权登录 -  开放能力接入流程
作者	版本	日期	Email
陈建华	V0.1.02	2018年05月23日	dyoungchen@QQ.com
陈建华	V1.1.0	2018年06月11日	dyoungchen@QQ.com

1.项目说明
A.	介绍:INCID是影链的核心, INCID 包含用户个人中心,身份认证.
B.	项目代号:Zeus
2.接口能力
A.	授权登录
B.	身份认证
C.	信用指数
D.	影响力指数
E.	INC支付
F.	影响力代币化(自建公链节点)
3.接入流程
A.	资质认证
现阶段有技术人员直接对接,给相应的开发者账号,开放平台上线后允许开发者(合作者)自主进行申请.(下同)
B.	成为开发者
C.	添加应用
D.	获取权限(场景)接口
E.	阅读相关开发技术文档
4. 网站授权登录开发指南
A.	准备工作
C.	网站应用授权登录是基于OAuth2.0协议标准构建的INCID OAuth2.0授权登录系统。在进行INCID OAuth2 授权登录接入之前，需要注册成为开发者，并拥有一个已审核通过的网站应用，并获得相应的AppID和AppSecret. 现阶段有技术人员直接对接,给相应的开发者账号,开放平台上线后允许开发者(合作者)自主进行申请.
B.	授权流程说明

INC OAuth2.0授权登录让INC用户使用INC身份安全登录第三方应用或网站，在INC用户授权登录已接入INC OAuth2.0的第三方应用后，第三方可以获取到用户的接口调用凭证（access_token），通过access_token可以进行INC开放平台授权关系接口调用，从而可实现获取INC用户基本开放信息和帮助用户实现基础开放功能等。
INC OAuth2.0授权登录目前支持authorization_code模式，适用于拥有server端的应用授权。该模式整体流程为：

(a)	第三方发起INC授权登录请求，INC用户登录授权后， INC重定向到第三方网站，并且带上授权临时票据code参数；
(b)	通过code参数加上AppID和AppSecret等，通过API换取access_token；
(c)	通过access_token进行接口调用，获取用户基本数据资源或帮助用户实现基本操作。

获取access_token时序图：

	 
C.	授权登录接入流程
1)请求CODE
第三方使用网站应用授权登录前请注意已获取相应网页授权作用域（scope=snsapi_base），然后通过拼接请求授权 code 链接,然后在PC端打开以下该链接,如:
https://www.incid.org/#/login?appid=APPID&goto =GOTO_URI&response_type=code&scope=SCOPE&state=STATE&grant_type=authorization_code
若不出现账号和密码输入框，请检查参数是否填写错误，如 response_type填写不正确
接口使用 GET 请求

参数说明
参数	是否必须	说明
appid	是	应用唯一标识
grant_type
	是	必须authorization_code

goto	是	请使用经过base64编码之后的回调业务url地址,该参数表示用户授权登录之前停留的业务地址,INC授权请求后原样带回给第三方。
scope
	是	应用授权作用域，填写snsapi_login即可
response_type	是	必须填写为CODE
state	是	用于保持请求和回调的状态，授权请求后原样带回给第三方。该参数可用于防止csrf攻击（跨站请求伪造攻击），建议第三方带上该参数，可设置为简单的随机数加session进行校验,(此处为用户自定义的标识字段可以为你自己的任意标识字符串用于回调校验)

返回说明
用户允许授权后，将会重定向到在开放平台上填写的回调地址上，并且带上code和state参数
redirect_uri?code=CODE&state=STATE
若授权出错，则重定向后不会带上code参数，仅会带上state参数
redirect_uri?state=STATE
第二步：通过code获取access_token
获取授权 code 后,INC 会定位到处理授权登录的回调方法中
服务端通过 code获取access_token, 拼接以下 URL
https://auth.incid.org/token?appid=APPID&secret=SECRET&grant_type=authorization_code&code=code&scope=snsapi_base
接口使用 GET请求, 以下参数通过 GET 发送
参数说明
参数	是否必须	说明
appid	是	应用唯一标识，在开放平台提交应用审核通过后获得,或者联系技术支持
secret	是	应用密钥AppSecret，在INC开放平台提交应用审核通过后获得
grant_type	是	填authorization_code
code	是	填写第一步获取的授权码code参数
scope	是	填snsapi_base

正确的返回：


{
    "status": 1,
    "msg": "ok",
    "access_token": "mz462r9whnrc0nnjte9twpe3d7odsifn",
    "expire_in": 7200,
    "refresh_token": "mwvrnjqvwezlhdmmhjyoqqpju4681wwa",
    "refresh_token_expire_in": 2592000,
    "scope": "snsapi_base ",
"open_uid": "304299781566496769",
"union_id": "304299781566496768",

}
参数说明
参数	说明
access_token	接口调用凭证
expires_in	access_token接口调用凭证超时时间，单位（秒）,现为两小时
refresh_token	用于请求新的access_token
refresh_token_expire_in	refresh_token超时时间，单位（秒）,现有效期30天
open_ uid	授权用户唯一标识
scope	用户授权的作用域原样返回
union_id	授权用户的联合 id (备用于同一个开发者多个应用的联合)
错误返回：
{
    "status": 40064,
    "msg": "请使用refresh_token协议获取新token ",
    "refresh_token": "mwvrnjqvwezlhdmmhjyoqqpju4681wwa",
    "refresh_token_expire_in": 2592000
}

请走重新授权流程获取新的 access_token
注意：
1、Appsecret 是应用接口使用密钥，泄漏后将可能导致应用数据泄漏、应用的用户数据泄漏等高风险后果；存储在客户端，极有可能被恶意窃取（如反编译获取Appsecret）；
2、access_token 为用户授权第三方应用发起接口调用的凭证（相当于用户登录态），存储在客户端，可能出现恶意获取access_token 后导致的用户数据泄漏、用户INC相关接口功能被恶意发起等行为；
3、refresh_token 为用户授权第三方应用的长效凭证，仅用于刷新access_token，但泄漏后相当于access_token 泄漏，风险同上。

建议将secret、用户数据（如access_token）放在App云端服务器，由云端中转接口调用请求。

第三步：刷新access_token有效期
access_token是调用授权关系接口的调用凭证，由于access_token有效期（目前为2个小时）较短，当access_token超时后，可以使用refresh_token进行刷新.

注:
1.若access_token已超时，那么进行refresh_token会获取一个新的access_token，新的超时时间；
2. refresh_token拥有较长的有效期（30天），当refresh_token失效的后，需要用户重新授权。

请求方法
获取access_token后，请求以下链接进刷新 access_token：

https://auth.incid.org/token?appid=APPID&secret=SECRET&grant_type= refresh_token&code=CODE&scope=snsapi_base&mode=authorization_code&refresh_token=XXXXXXX



参数说明
参数	是否必须	说明
appid	是	应用唯一标识
secret	是	应用密钥AppSecret，
code	是	必须为CODE
grant_type	是	填refresh_token
scope	是	填snsapi_base
refresh_token	是	上次获取的refresh_token字符串
mode	是	填写authorization_cod

返回说明
正确的返回：
	
{
    "status": 1,
    "msg": "ok",
    "access_token": "mz462r9whnrc0nnjte9twpe3d7odsifn",
    "expire_in": 7200,
    "refresh_token": "mwvrnjqvwezlhdmmhjyoqqpju4681wwa",
    "refresh_token_expire_in": 2592000,
    "scope": "snsapi_base ",
"open_uid": "304299781566496769",
"union_id": "304299781566496768",

}
返回参数说明
参数	说明
access_token	接口调用凭证
expires_in	access_token接口调用凭证超时时间，单位（秒）,现为两小时
refresh_token	用于请求新的access_token
refresh_token_expire_in	refresh_token超时时间，单位（秒）,现有效期30天
open_ uid	授权用户唯一标识
scope	用户授权的作用域原样返回
union_id	授权用户的联合 id (备用于同一个开发者多个应用的联合)
scope	用户授权的作用域原样返回



错误返回样例：
{
    "status": 400519,
    "msg": "refresh token不存在或者已经失效,请走初始化Token申请接口获取新的Token和Refresh Token！"
}
第四步：通过access_token调用用户信息接口
获取access_token后，进行接口调用，有以下前提：
1. access_token有效且未超时；
2. INC用户已授权给第三方应用帐号。

拼接请求地址
https://auth.incid.org/open/user/info_by_openuid
?access_token=ACCESSTOKEN&open_uid=OPEN_UID
使用 GET 请求
参数说明
参数	是否必须	说明
access_token	是	获取的access_token
open_uid	是	获取的 open_uid

正确的返回信息
{
    "status": 1,
    "msg": "成功",
    "msg_code": 0,
    "data": {
        "nationality": "China",
        "verify": "0", 
        "email": "1367784103@qq.com",
        "open_uid": "313884273138466816"
    }
}
返回数据说明:
verify: 表示认证状态 0表示未认证1表示已经认证 2表示已提交,待动审核,3表示手动认证审核失败
nationality:表示国籍
错误返回:
{
    "status": -1,
    "msg": "用户不存在",
    "msg_code": 0,
    "data": []
}



 	什么是授权临时票据（code）？
答：第三方通过code进行获取access_token的时候需要用到，code一次性使用过后立即失效，同时 code只能成功换取一次access_token即失效。code的临时性和一次保障了INC授权登录的安全性。第三方可通过使用https和state参数，进一步加强自身授权登录的安全性。
 	什么是授权作用域（scope）？
答：授权作用域（scope）代表用户授权给第三方的接口权限，第三方应用需要向INC开放平台申请使用相应scope的权限后，使用文档所述方式让用户进行授权，经过用户授权，获取到相应access_token后方可对接口进行调用。

5.Q&A
1) Python request库遇到 403错误,
 req.add_header("User-Agent","Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36")  
2)Python request库遇到 SSL错误
关闭 ssl 验证即可解决问题
3)

6.技术支持
陈工
Wechat : chen1367784103
手机: 18721662103
请说明来意:  INC 开放平台对接

INC 开放平台
2018年05月22日
</pre>

