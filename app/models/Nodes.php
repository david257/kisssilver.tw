<?php
namespace app\models;

class Nodes
{
    static $tablename = "nodes";

    static function getNodes()
    {
        $nodes = [
            "10" => [
                "title" => "會員管理",
                "child" => [
                    "11" => [
                        "title" => "會員列表",
                        "url" => admin_link('Customer/index'),
                    ],
                    "12" => [
                        "title" => "會員等級",
                        "url" => admin_link('Customer/groups')
                    ],
                ]
            ],
            "20" => [
                "title" => "訂單管理",
                "child" => [
                    "21" => [
                        "title" => "訂單清單",
                        "url" => admin_link('Orders/index'),
                    ],
                    "22" => [
                        "title" => "退貨列表",
                        "url" => admin_link('Orders/returns'),
                    ],
                ]
            ],
            "30" => [
                "title" => "產品管理",
                "child" => [
                    "31" => [
                        "title" => "產品清單",
                        "url" => admin_link('Product/index'),
                    ],
                    "32" => [
                        "title" => "分類管理",
                        "url" => admin_link('Category/index')
                    ],
                    "33" => [
                        "title" => "商品規格",
                        "url" => admin_link('VariationOptions/index')
                    ],
                    "34" => [
                        "title" => "商品屬性",
                        "url" => admin_link('Attribute/index')
                    ],
                    "39" => [
                        "title" => "贈品",
                        "url" => admin_link('Gift/index')
                    ],
                    "35" => [
                        "title" => "庫存表",
                        "url" => admin_link('Stocks/index')
                    ],
                ]
            ],
            "40" => [
                "title" => "直播開獎",
                "child" => [
                    "41" => [
                        "title" => "直播列表",
                        "url" => admin_link('Lives/index'),
                    ],
                ]
            ],
            "50" => [
                "title" => "促銷與折扣",
                "url" => admin_link('Promotion/index'),
                "child" => []
            ],
            "60" => [
                "title" => "廣告圖管理",
                "url" => admin_link('Banner/index'),
                "child" => []
            ],
            "70" => [
                "title" => "內容管理",
                "child" => [
                    "71" => [
                        "title" => "新聞",
                        "url" => admin_link('News/index'),
                    ],
                    "72" => [
                        "title" => "單頁",
                        "url" => admin_link('Page/index')
                    ],
                ]
            ],
            "80" => [
                "title" => "優惠券管理",
                "child" => [
                    "81" => [
                        "title" => "優惠券列表",
                        "url" => admin_link('Coupon/index'),
                    ],
                    "85" => [
                        "title" => "優惠券自動發放設置",
                        "url" => admin_link('Coupon/auto_send_rule'),
                    ],
                ]
            ],
            "450" => [
                "title" => "折扣碼管理",
                "url" => admin_link('CouponCodes/index'),
            ],
			"400" => [
				"title" => "門市結帳",
				"child" => [
					"401" => [
                        "title" => "門市結帳",
                        "url" => admin_link('StoreOrder/customer')
                    ],
                    "402" => [
                        "title" => "門市結帳記錄",
                        "url" => admin_link('StoreOrder/index')
                    ],
				]
			],
            "300" => [
                "title" => "統計報表",
                "child" => [
                    "301" => [
                        "title" => "訂單報表",
                        "url" => admin_link('Orders/report'),
                    ],
                    "310" => [
                        "title" => "產品銷量榜",
                        "url" => admin_link('Product/saleReport')
                    ],
                    "320" => [
                        "title" => "會員累計消費",
                        "url" => admin_link('Customer/orders')
                    ],
                    /*"330" => [
                        "title" => "門市結賬報表",
                        "url" => admin_link('StoreOrder/report')
                    ],*/
                ]
            ],
            "350" => [
                "title" => "訂閱用戶",
                "url" => admin_link('Subscribe/index'),
                "child" => []
            ],
            "90" => [
                "title" => "帳號設置",
                "child" => [
                    "91" => [
                        "title" => "帳號列表",
                        "url" => admin_link('User/index'),
                    ],
                    "92" => [
                        "title" => "角色管理",
                        "url" => admin_link('Role/index')
                    ],
                ]
            ],
            "200" => [
                "title" => "系統設置",
                "child" => [
                    "201" => [
                        "title" => "基本設置",
                        "url" => admin_link('Setting/index'),
                    ],
                    "202" => [
                        "title" => "銷售據點",
                        "url" => admin_link('StoreNetwork/index'),
                    ],
                    "205" => [
                        "title" => "郵件範本",
                        "url" => admin_link('Email/index'),
                    ],
                ]
            ],
        ];

        return $nodes;
    }

    public static function getHeaderNodes()
    {
        return ["11","21","401"];
    }
}