<?php
namespace app\models;

use think\facade\Db;

class Setting
{
    static $tablename = "settings";
    static $setting_types = [
        "info" => [
            "title" => "基本設置",
            "rows" => [
                "sitename" => "網站名稱",
                "seo_title" => "首頁SEO標題",
                "seo_keywords" => "首頁SEO關鍵字",
                "seo_desc" => "首頁SEO描述"
            ]
        ],
        "mantance" => [
            "title" => "網站維護通知"
        ],
        "credits" => [
            "title" => "紅利點數換算"
        ],
        "line" => [
            "title" => "Line客服"
        ],
        "smtp" => [
            "title" => "發件設置"
        ],
        "email" => [
            "title" => "郵件範本",
            "rows" => [
                "email_reg" => "註冊郵件",
                "email_forget_passwd" => "找回密碼郵件",
                "email_order" => "訂購通知",
                "email_subscribe" => "訂閱通知",
            ]
        ],
        "map" => [
            "title" => "谷歌地圖金鑰",
        ],
        "pay" => [
            "title" => "支付金鑰",
            "rows" => [
                "email_reg" => "金流",
            ],
        ],
        "express" => [
            "title" => "物流跟蹤",
            "rows" => [
                "email_reg" => "物流",
            ],
        ],
        "login" => [
            "title" => "登入金鑰",
            "rows" => [
                "fb" => "FB金鑰",
                "google" => "Google金鑰",
            ]
        ],
        "fans" => [
            "title" => "粉絲群"
        ],
        "trackcode" => [
            "title" => "統計代碼"
        ],
    ];
}
