<?php
//定义回调URL通用的URL
define('URL_CALLBACK', 'http://127.0.0.1/callback/?type=');

return array(
    //腾讯QQ登录配置正式
    'THINK_SDK_QQ' => array(
        'APP_KEY'    => '123124656', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '1231245646', //应用注册成功后分配的KEY
        'CALLBACK'   => URL_CALLBACK . 'qq',
    ),
);
