<?php
namespace Home\Model;
use Think\Model;
class SpecialBusinessRatioModel extends Model {
	
	public function getAllSpecialBusinessRatio(){
		
		$res = $this->select();
		foreach ($res as $key => $value) {
			$res[$key]['ratio'] *= 100;
		}
		return $res;
	}
	
	public function addSpecialBusinessRatio($data){
		$data['ratio'] *= 0.01;
		// $this -> add($data);
	}
	public function edtiSpecialBusinessRatio($id,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('ratio',$ratio*0.01);
	}
	public function deleteSpecialBusinessRatioById($id){
		$condition = array();
		$condition['id'] = $id;
		// $this -> where($condition) -> delete();
	}
}
?>