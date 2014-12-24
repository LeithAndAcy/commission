<?php
namespace Home\Model;
use Think\Model;
class InsuranceFundModel extends Model {
	
	public function getAllInsuranceFund(){
		
		$res = $this->select();
		return $res;
	}
	
	public function addItem($data){
		print_r($data);
		// $this -> add($data);
	}
	public function editInsuranceAndFund($id,$data){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> save($data);
	}
	public function deleteItemById($id){
		$condition = array();
		$condition['id'] = $id;
		// $this -> where($condition) -> delete();
	}
}
?>