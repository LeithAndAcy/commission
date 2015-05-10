<?php
namespace Home\Model;
use Think\Model;
class FeeRatioModel extends Model {
	
	public function getAllFeeRatio(){
		$res = $this->select();
		foreach ($res as $key => $value) {
			$res[$key]['fee_ratio'] *= 100;
		}
		return $res;
	}
	public function getFeeRatio($salesman_id){
		$condition = array();
		// $condition['salesman_id'] = $salesman_id;
		$condition['salesman_id'] = "all_salesman";
		$res = $this -> where($condition) -> getField('fee_ratio');
		return $res;	
	}
	public function editItem($id,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('fee_ratio',$ratio*0.01);
	}
	public function addItem($data){
		$data['fee_ratio'] = $data['fee_ratio']*0.01;
		$this -> add($data);
	}
	public function deleteItem($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}
}
?>