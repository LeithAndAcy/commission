<?php
namespace Home\Model;
use Think\Model;
class WageDeductionModel extends Model {
	
	public function getAllItems(){
		$res = $this->select();
		
		return $res;
	}
	public function editItem($id,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('ratio',$ratio * 0.01);
	}
	public function addItem($data){
		$data['ratio'] *= 0.01;
		$this -> add($data);	
	}
	public function deleteItem($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition)-> delete();
	}
}
?>