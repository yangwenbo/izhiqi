<?php

return array(
    //       'SESSION_TYPE' => 'redis',
    // 'SESSION_HOST'	=> '127.0.0.1', //填写redis服务器地址
    // 'SESSION_PORT'=> 6379, //redis端口,默认6379
    // 'SESSION_EXPIRE'=> 1800, //session过期时间(秒), 30分钟
    // 'SESSION_TIMEOUT'=> 60, //redis连接超时时间,默认60秒		

    'SHOW_PAGE_TRACE' => true,
    'IS_COMPRESS' => false, //是否加载压缩后的js，css
    'DATA_CACHE_TYPE' => 'Redis', // 数据缓存类型,
    'REDIS_HOST' => '127.0.0.1',
// 		'REDIS_PORT' => 6397,
    //邮箱验证，找回密码，重绑邮箱
    'VERIFYEMAILREDIS' => array(
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => false,
        'persistent' => false,
    ),
    'LOCALREDIS' => array(
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => false,
        'persistent' => false,
    ),
    // 站点配置
    'DOMAINNAME' => 'http://aizh.local',
    'TMPL_ACTION_SUCCESS' => './izhiqi/Tpl/Home/Public/error.html', //
    'TMPL_ACTION_ERROR' => './izhiqi/Tpl/Home/Public/error.html',
    'IP_DATAS_PATH' => WEBSITE.'Pulbic/tinyipdata.dat',
    'UPLOAD' => 'Upload/',
    'UPLOAD_AVARTAR' => 'Upload/avatar',
    'AVATAR_THUMB_PREFIX' => 'm_',
    'UPLOAD_LOGO' => 'Upload/logo',
    'UPLOAD_CERTIFICATE' => 'Upload/certificate',
    'TMPL_PARSE_STRING' => array(
        //public
        //'__PUBLIC__' => __ROOT__ . '/Public',
        '__JSURL__' => WEBSITE . 'Public/js',
        '__CSSURL__' => WEBSITE . 'Public/css',
        '__IMGURL__' => WEBSITE . 'Public/images',
    ),
    // 常规配置
    'URL_MODEL' => 1, // 如果你的环境不支持PATHINFO 请设置为3
    'URL_HTML_SUFFIX' => '.html',
    'URL_CASE_INSENSITIVE' => true, // 默认false 表示URL区分大小写 true则表示不区分大
    'SECRET_KEY' => 'hffhiqwefdis1235w*#@#', // 加密字符串
    'URL_ROUTER_ON' => true, //开启路由
    'URL_ROUTE_RULES' => array(//定义路由规则
        'job/:cid\d/:step\d' => 'job/nextbystep',
    ),
    'URL_REVERSE_ROUTE_RULES' => array(
        'job' => "job/%d",
    ),
    //邮件服务器配置
    'SMTP_SERVER' => array(
        'displayname' => '爱职网',
        'server' => 'smtp.exmail.qq.com',
        'port' => '25',
        'user' => '1302623964@qq.com',
        'password' => 'a123456'
    ),
    // 支付方式
    'PAYMENT_METHODS' => array(
        // 支付宝
        'ALIPAY' => array(
            'NAME' => '支付宝',
            'DESC' => '支付宝（中国）网络技术有限公司是国内领先的独立第三方支付平台，是阿里巴巴集团的关联公司。',
            //商户号
            'PARTNER_ID' => '78945464654646',
            //商家email
            'SELLER_EMAIL' => 'nofuyun@gmail.com',
            //密钥
            'KEY' => ""
        )
    ),
);
?>
