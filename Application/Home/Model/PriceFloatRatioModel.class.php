<?php
namespace Home\Model;
use Think\Model;
class PriceFloatRatioModel extends Model {
	
	public function getAllPriceFloatRatio(){
		$res = $this->select();
		foreach ($res as $key => $value) {
			$res[$key]['ratio'] *= 100;
		}
		return $res;
	}
	
	public function addItem($data){
		$data['ratio'] *= 0.01;
		// $this -> add($data);
	}
	public function edtiPriceFloatRatio($id,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('ratio',$ratio*0.01);
	}
	public function deleteItemById($id){
		$condition = array();
		$condition['id'] = $id;
		// $this -> where($condition) -> delete();
	}
}
?>