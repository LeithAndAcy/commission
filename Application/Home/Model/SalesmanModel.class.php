<?php
namespace Home\Model;
use Think\Model;
class SalesmanModel extends Model {
	
	public function getAllSalesman(){
		
		$res = $this->getField('id,salesman_id,salesman_name,status');
		return $res;
	}
	
	public function getAllSalesmanInfo(){
		$res = $this -> select();
		return $res;
	}
	
	public function editSalesman($id,$data){
		$condition = array();
		$condition['id'] =$id;
		$this -> where($condition) -> save($data);
	}
	
	public function addItem($data){
		$this -> add($data);
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
	
	public function getSalesmanNameBySalesmanId($data){
		
	}
	
}
?>