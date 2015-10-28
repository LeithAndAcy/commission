<?php
namespace Home\Model;
use Think\Model;
class SpecialApprovePriceFloatRatioModel extends Model {
	
	public function getAllItems(){
		$res = $this -> select();
		foreach ($res as $key => $value) {
			$res[$key]['ratio'] *= 100;
		}
		return $res;
	}
	
	public function addItem($data){
		$data['ratio'] *= 0.01;
		$this -> add($data);
		
	}
	public function editItem($id,$data){
		$condition = array();
		$condition['id'] = $id;
		$data['ratio'] *= 0.01;
		$this -> where($condition) -> save($data);
	}
	public function deleteItemById($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}
	public function getRatio($classification_id,$area){
		$condition = array();
		$condition['classification_id'] = $classification_id;
		$condition['area'] = $area;
		$res = $this -> where($condition) -> getField('ratio');
		return $res;
	}
	private function checkDuplicate($data){
		$condition = array();
		$condition['classification_id'] = $data['classification_id'];
		$condition['area'] = $data['area'];
		$res = $this -> where($condition)-> find();
		if($res){
			return true;
		}else{
			return false;
		}
	}
}
?>