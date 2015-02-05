<?php
namespace Home\Model;
use Think\Model;
class LengthLimitModel extends Model {
	
	public function getAllItems(){
		$res = $this -> query("select id,low_length,high_length,ltrim(str(limit * 100,12,2)) as limit from commission_length_limit");
		return $res;
	}
	public function editItem($id,$limit){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('limit',$limit * 0.01);
	}
	public function addItem($data){
		$data['limit'] *= 0.01;
		if(($this ->checkDuplicate($data))){
			$condition = array();
			$condition['low_length'] = $data['low_length'];
			$condition['high_length'] = $data['high_length'];
			$this -> where($condition) -> save($data);
			return true;
		}else{
			$temp_low_length = $data['low_length'];
			$temp_high_length = $data['high_length'];
			$temp = $this -> query("select low_length,high_length from commission_length_limit where((low_length < '$temp_low_length' AND high_length >'$temp_high_length') OR (low_length < '$temp_high_length' AND high_length >'$temp_low_length' ))");
			if($temp){
				return false;
			}else{
				$this -> add($data);
				return true;
			}
			
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