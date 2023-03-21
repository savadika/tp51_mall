<?php
/**
 * User:ylf
 * Date:2022/12/29
 * FileName:token.php
 */
use app\api\service\Token as TokenService;

return [
    'wepay_config'=>[
        'token'          => TokenService::getToken(),
        'appid'          => config('token.APP_ID'),       //诚行小程序demo
        'appsecret'      => config('token.APP_SECRET'), //appsecrect
        'encodingaeskey' => 'BJIUzE0gqlWy0GxfPp4J1oPTBmOrNDIGPNav1YFH5Z5',
        // 配置商户支付参数
        'mch_id'         => "1639826803",
        'mch_key'        => 'bjgi6dnZ48IkPYDQMKNF8T8uXTMKzmJt',
        // 配置商户支付双向证书目录 （p12 | key,cert 二选一，两者都配置时p12优先）
        'ssl_p12'        => __DIR__ . DIRECTORY_SEPARATOR . 'cert' . DIRECTORY_SEPARATOR . 'apiclient_cert.p12',
//         'ssl_key'        => __DIR__ . DIRECTORY_SEPARATOR . 'cert' . DIRECTORY_SEPARATOR . 'apiclient_key.pem',
//         'ssl_cer'        => __DIR__ . DIRECTORY_SEPARATOR . 'cert' . DIRECTORY_SEPARATOR . 'apiclient_cert.pem',
        // 配置缓存目录，需要拥有写权限
        'cache_path'     => ''
    ]
];