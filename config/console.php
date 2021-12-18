<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        \app\command\SendCoupons::class,
        \app\command\GroupUpgrade::class,
        \app\command\Import::class,
		\app\command\Stock::class,
        \app\command\StockSysPush::class,
        \app\command\StockSysPull::class
    ],
];
