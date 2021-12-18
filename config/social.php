<?php
/**
 * User: liliuwei
 * Date: 2017/5/23
 */
$config = get_setting();
$setting = $config["setting"];
$domain = request()->domain();
return [

    //Google登錄配置
    'google' => [
        'app_key' => isset($setting["google"]["clientid"])?$setting["google"]["clientid"]:"", //應用註冊成功後分配的 APP ID
        'app_secret' => isset($setting["google"]["secret"])?$setting["google"]["secret"]:"",  //應用註冊成功後分配的KEY
        'callback' => $domain.'/oauth/callback/type/google', // 應用回調地址
    ],
    //Facebook登錄配置
    'facebook' => [
        'app_key' => isset($setting["facebook"]["appid"])?$setting["facebook"]["appid"]:"", //應用註冊成功後分配的 APP ID
        'app_secret' => isset($setting["facebook"]["secret"])?$setting["facebook"]["secret"]:"",  //應用註冊成功後分配的KEY
        'callback' => $domain.'/oauth/callback/type/facebook', // 應用回調地址
    ],
];
