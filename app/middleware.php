<?php
// 全域中介軟體定義文件
return [
    // 全域請求緩存
    // \think\middleware\CheckRequestCache::class,
    // 多語言載入
    // \think\middleware\LoadLangPack::class,
    // Session初始化
    \think\middleware\SessionInit::class
];
