<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class Import extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('import')
            ->setDescription('the import command');        
    }

    protected function execute(Input $input, Output $output)
    {
        //$this->updatePostcode();
    }

    private function  updatePostcode()
    {
        $city = '花蓮縣';
        $areas = '花蓮市 970
新城鄉 971
秀林鄉 972
吉安鄉 973
壽豐鄉 974
鳳林鎮 975
光復鄉 976
豐濱鄉 977
瑞穗鄉 978
萬榮鄉 979
玉里鎮 981
卓溪鄉 982
富里鄉 983';

       $_areas = explode("\n", $areas);
       $cityId = Db::name("countries")->where("pid", 1)->where("name", $city)->value("id");
       if(!empty($_areas)) {
           foreach($_areas as $k => $v) {
              $areaArr = explode(' ', $v);
              $areaName = trim($areaArr[0]);
              $areaPostcode = trim($areaArr[1]);
              $ret = Db::name("countries")->where("pid", $cityId)->where("name", $areaName)->update(["postcode" => $areaPostcode]);
              var_dump($ret);
           }
       }
    }


}
