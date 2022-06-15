<?php
// +----------------------------------------------------------------------
// | 應用設置
// +----------------------------------------------------------------------

return [
    // 應用地址
    'app_host'         => env('app.host', ''),
    // 應用的命名空間
    'app_namespace'    => '',
    // 是否啟用路由
    'with_route'       => true,
    // 默認應用
    'default_app'      => 'front',
    // 默認時區
    'default_timezone' => 'PRC',

    // 應用映射（自動多應用模式有效）
    'app_map'          => [],
    // 域名綁定（自動多應用模式有效）
    'domain_bind'      => [],
    // 禁止URL訪問的應用列表（自動多應用模式有效）
    'deny_app_list'    => [],

    // 異常頁面的模板文件
    'exception_tmpl'   => "",

    // 錯誤顯示信息,非調試模式有效
    'error_message'    => '頁面錯誤！請稍後再試～',
    // 顯示錯誤信息
    'show_error_msg'   => true,
    //'exception_tmpl'   => \think\facade\App::getAppPath() . 'view/public/404.php',
    'http_exception_template' => [
        404 =>  "/public/static/error/404.html",
        500 =>  "/public/static/error/404.html",
    ]
];
