<?php
namespace Home\Model;
use Think\Model;
class NormalBusinessRatioModel extends Model {
	
	public function getAllNormalBusinessRatio(){
		
		$res = $this->select();
		foreach ($res as $key => $value) {
			$res[$key]['ratio'] *= 100;
		}
		return $res;
	}
	
	public function addNormalBusinessRatio($data){
		$data['ratio'] *= 0.01;
		$this -> add($data);
	}
	public function edtiNormalBusinessRatio($id,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('ratio',$ratio*0.01);
	}
	public function deleteNormalBusinessRatioById($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}
}
?>