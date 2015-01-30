<?php
namespace Home\Model;
use Think\Model;
class NormalProfitRatioModel extends Model {
	
	public function getAllNormalProfitRatio(){
		
		$res = $this->select();
		foreach ($res as $key => $value) {
			$res[$key]['ratio'] *= 100;
		}
		return $res;
	}
	
	public function addNormalProfitRatio($data){
		$data['ratio'] *= 0.01;
		$this -> add($data);
	}
	public function edtiNormalProfitRatio($id,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('ratio',$ratio*0.01);
	}
	public function deleteNormalProfitRatioById($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}
}
?>