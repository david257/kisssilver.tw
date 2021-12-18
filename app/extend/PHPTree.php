<?php
namespace app\extend;
/**
 * @name PHPTree 
 * @author crazymus < QQ:291445576 >
 * @des PHP生成樹形結構,無限多級分類
 * @version 1.2.0
 * @Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * @updated 2015-08-26

 */
class PHPTree{	

	protected static $config = array(
		/* 主鍵 */
		'primary_key' 	=> 'id',
		/* 父鍵 */
		'parent_key'  	=> 'parent_id',
		/* 展開屬性 */
		'expanded_key'  => 'expanded',
		/* 葉子節點屬性 */
		'leaf_key'      => 'leaf',
		/* 孩子節點屬性 */
		'children_key'  => 'children',
		/* 是否展開子節點 */
		'expanded'    	=> false
	);
	
	/* 結果集 */
	protected static $result = array();
	
	/* 層次暫存 */
	protected static $level = array();
	/**
	 * @name 生成樹形結構
	 * @param array 二維陣列
	 * @return mixed 多維陣列
	 */
	public static function makeTree($data,$options=array() ){
		$dataset = self::buildData($data,$options);
		$r = self::makeTreeCore(0,$dataset,'normal');
		return $r;
	}
	
	/* 生成線性結構, 便於HTML輸出, 參數同上 */
	public static function makeTreeForHtml($data,$options=array()){
	
		$dataset = self::buildData($data,$options);
		$r = self::makeTreeCore(0,$dataset,'linear');
		return $r;	
	}
	
	/* 格式化資料, 私有方法 */
	private static function buildData($data,$options){
		$config = array_merge(self::$config,$options);
		self::$config = $config;
		extract($config);

		$r = array();
		foreach($data as $item){
			$id = $item[$primary_key];
			$parent_id = $item[$parent_key];
			$r[$parent_id][$id] = $item;
		}
		
		return $r;
	}
	
	/* 生成樹核心, 私有方法  */
	private static function makeTreeCore($index,$data,$type='linear')
	{
		extract(self::$config);
		foreach($data[$index] as $id=>$item)
		{
			if($type=='normal'){
				if(isset($data[$id]))
				{
					$item[$expanded_key]= self::$config['expanded'];
					$item[$children_key]= self::makeTreeCore($id,$data,$type);
				}
				else
				{
					$item[$leaf_key]= true;  
				}
				$r[] = $item;
			}else if($type=='linear'){
				$parent_id = $item[$parent_key];
				self::$level[$id] = $index==0?0:self::$level[$parent_id]+1;
				$item['level'] = self::$level[$id];
				self::$result[] = $item;
				if(isset($data[$id])){
					self::makeTreeCore($id,$data,$type);
				}
				
				$r = self::$result;
			}
		}
		return $r;
	}
}


?>
