<?php
namespace Home\Model;
use Think\Model;
class SalesmanModel extends Model {
	
	public function getAllSalesman(){
		
		$res = $this->getField('id,salesman_id,salesman_name,status,onboard_status');
		return $res;
	}
	
	public function getAllSalesmanInfo(){
		$res = $this -> select();
		return $res;
	}
	public function getOnboardSalesmanInfo(){
		$condition = array();
		$condition['onboard_status'] = "在职";
		$res = $this -> where($condition) -> select();
		return $res;
	}
	
	public function editSalesman($id,$data){
		$condition = array();
		$condition['id'] =$id;
		$this -> where($condition) -> save($data);
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
	public function deleteItem($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> limit('1') ->delete();
	}
	
	public function checkDuplicateSalesmanId($salesman_id){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$res = $this-> where($condition)->find();
		if($res){
			return false;  //名字重复，新名字不能用
		}else{
			return true; //名字没重复，新名字能用	
		}
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
	public function addSalesmanName($array_data){
		$all_salesmen = $this-> select();
		foreach ($array_data as $key => $value) {
			foreach ($all_salesmen as $kk => $vv) {
				if($value['salesman_id'] == $vv['salesman_id']){
					$array_data[$key]['salesman_name'] = $vv['salesman_name'];
					break;
				}
			}
		}
		return $array_data;
	}
	public function getStatus($salesman_id){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$res = $this -> where($condition) -> getField('status');
		return $res;
	}
	public function getSalesmanInfo($salesman_id){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$res = $this -> where($condition) -> find();
		return $res;
	}
	public function getSalesmanName($salesman_id){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$res = $this -> where($condition) -> getField('salesman_name');
		return $res;
	}
}
?>