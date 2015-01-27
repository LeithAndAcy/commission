<?php
namespace Home\Model;
use Think\Model;
class LengthLimitModel extends Model {
	
	public function getAllItems(){
		$res = $this->select();
		foreach ($res as $key => $value) {
			$res[$key]['limit'] *= 100;
		}
		return $res;
	}
	public function editItem($id,$limit){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('limit',$limit * 0.01);
	}
	public function addItem($data){
		$data['limit'] *= 0.01;
		$this -> add($data);	
	}
	public function deleteItem($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition)-> delete();
	}
}
?>