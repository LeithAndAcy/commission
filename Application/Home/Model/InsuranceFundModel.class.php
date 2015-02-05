<?php
namespace Home\Model;
use Think\Model;
class InsuranceFundModel extends Model {
	
	public function getAllInsuranceFund(){
		
		$res = $this -> query("select id,salesman_id,ltrim(str(insurance,9,2)) as insurance,ltrim(str(fund,9,2)) as fund from commission_insurance_fund");
		return $res;
	}
	
	public function addItem($data){
		if(($this ->checkDuplicate($data['salesman_id']))){
			$condition = array();
			$condition['salesman_id'] = $data['salesman_id'];
			$this -> where($condition) -> save($data);
		}else{
			$this -> add($data);
		}
	}
	public function editInsuranceAndFund($id,$data){
		$condition = array();
		$condition['id'] = $id;
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