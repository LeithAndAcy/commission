<?php
namespace Home\Model;
use Think\Model;
class SpecialApprovePriceFloatRatioModel extends Model {
	
	public function getAllItems(){
		$res = $this -> select();
		foreach ($res as $key => $value) {
			$res[$key]['ratio'] *= 100;
		}
		return $res;
	}
	
	public function addItem($data){
		$data['ratio'] *= 0.01;
		$this -> add($data);
		
	}
	public function editItem($id,$data){
		$condition = array();
		$condition['id'] = $id;
		$data['ratio'] *= 0.01;
		$this -> where($condition) -> save($data);
	}
	public function deleteItemById($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}
	public function getAllHandledSpecialApprovePriceFloatRatio(){
		$arr = array();
		$res = $this -> select();
		foreach ($res as $key => $value) {
			$arr[$value['customer_id']][$value['inventory_id']]= $value['ratio'];
		}
		return $arr;
	}
	public function deleteAllItems(){
		$this -> where(1)->delete();
	}
	
}
?>