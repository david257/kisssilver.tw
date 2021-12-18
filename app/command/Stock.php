<?php
declare (strict_types = 1);

namespace app\command;

use app\models\Customer;
use app\models\Products;
use app\models\VariationCombinations;
use app\models\AttrValue;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\Exception;
use think\facade\Db;
use think\facade\View;

class Stock extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('stock')
            ->setDescription('庫存補貨通知');
    }

    protected function execute(Input $input, Output $output)
    {

        $config = get_setting();
        $setting = $config["setting"];
        try {

            if(!isset($setting['notify']['nostock_state']) || empty($setting['notify']['nostock_state'])) {
                throw new Exception("補貨通知未啟用");
            }

			$data['nooption_list'] = [];
            $list = Db::name(Products::$tablename)->where("void", 0)->where('state', 1)->where('stock_warning>=stock')->field('prodid, stock, stock_warning, prodcode, prodname')->select();
			if(!empty($list)) {
				foreach($list as $v) {
					$data['nooption_list'][$v['prodid']] = $v;
				}
			}

			$vlist = Db::name(Products::$tablename)->where("void", ">", 0)->where('state', 1)->field('prodid, prodcode, prodname')->select();
			$products = [];
			if(!empty($vlist)) {
				$prod_ids = [];
				foreach($vlist as $v) {
					$prod_ids[] = $v['prodid'];
					$products[$v['prodid']] = $v;
				}
				
				$product_options = [];
				if(!empty($prod_ids)) {
					$combinList = Db::name(VariationCombinations::$tablename)->where("vcproductid", "in", $prod_ids)->where('vcenabled', 1)->where('vstock_warning>=vcstock')->select();
					if(!empty($combinList)) {
						foreach($combinList as $v) {
							$valueIds = explode(",", $v['vcoptionids']);
							$vlist = Db::name(AttrValue::$tablename)->where('valueid', "IN", $valueIds)->select();
							$voptions = [];
							if(!empty($vlist)) {
								foreach($vlist as $vv) {
									$voptions[] = $vv['name'];
								}
							}
							$product_options[$v['vcproductid']][] = [
							    "voptions" => $voptions,
                                "vstock_warning" => $v["vstock_warning"],
                                "vcstock" => $v["vcstock"],
                                "vcsku" => $v["vcsku"],
                            ];;
						}
					}
				}
			}

			$data['products'] = $products;
			$data['product_options'] = $product_options;
			View::assign($data);
            $html = View::fetch("notify/stock");

			send_stock_notify($html);
        } catch (Exception $ex) {
            echo $ex->getMessage()."\n";
        }

        echo "stock notify compelate\n";
    }

}
