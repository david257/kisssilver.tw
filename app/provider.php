<?php
use app\ExceptionHandle;
use app\Request;

// 容器Provider定義文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
    'think\Paginator'    =>    'app\extend\Bootstrap'
];
