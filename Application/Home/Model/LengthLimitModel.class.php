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
		if(!($this ->checkDuplicate($data))){
			$data['limit'] *= 0.01;
			$this -> add($data);	
		}
	}
	public function deleteItem($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition)-> delete();
	}
	
	private function checkDuplicate($data){
		$condition = array();
		$condition['low_length'] = $data['low_length'];
		$condition['high_length'] = $data['high_length'];
		$res = $this -> where($condition)-> find();
		if($res){
			return true;
		}else{
			return false;
		}
	}
}
?>