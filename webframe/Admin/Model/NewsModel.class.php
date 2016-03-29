<?php
namespace Admin\Model;
use Think\Model;

class NewsModel extends Model{
	protected $tableName="news";

	/*
	添加操作
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("news")->data($data)->add()){
				$return=array(
					"status"=>true,
					"msg"=>"数据添加成功"
				);
			}
			else{
				$return=array(
					"status"=>false,
					"msg"=>"数据添加失败"
				);
			}
		}
		return $return;
	}
}