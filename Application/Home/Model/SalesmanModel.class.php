<?php
namespace Home\Model;
use Think\Model;
class SalesmanModel extends Model {
	
	public function getAllSalesman(){
		
		$res = $this->getField('id,salesman_id,name,status');
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
	public function getSalesmanNameBySalesmanId($data){
		
	}
	
}
?>