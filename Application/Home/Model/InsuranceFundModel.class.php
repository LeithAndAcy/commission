<?php
namespace Home\Model;
use Think\Model;
class InsuranceFundModel extends Model {
	
	public function getAllInsuranceFund(){
		
		$res = $this->select();
		foreach ($res as $key => $value) {
			$res[$key]['insurance'] *= 100;
			$res[$key]['fund'] *= 100;
		}
		return $res;
	}
	
	public function addItem($data){
		if(!($this ->checkDuplicate($data['salesman_id']))){
			$data['insurance'] *= 0.01;
			$data['fund'] *= 0.01;
			$this -> add($data);
		}
	}
	public function editInsuranceAndFund($id,$data){
		$condition = array();
		$condition['id'] = $id;
		$data['insurance'] *= 0.01;
		$data['fund'] *= 0.01;
		$this -> where($condition) -> save($data);
	}
	public function deleteItemById($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}
	public function checkDuplicate($salesman_id){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$res = $this-> where($condition)->find();
		if($res){
			return ture; 
		}else{
			return false; 
		}
	}
	public function getInsuranceFund($salesman_id){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$res = $this-> where($condition)->find();
		return $res;
	}
}
?>