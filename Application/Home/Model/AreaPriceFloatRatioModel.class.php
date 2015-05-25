<?php
namespace Home\Model;
use Think\Model;
class AreaPriceFloatRatioModel extends Model {
	
	public function getAllAreaPriceFloatRatio(){
		$res = $this -> query("select id,classification_id,area,ltrim(str(ratio * 100,12,2)) as ratio  from commission_area_price_float_ratio");
		return $res;
	}
	
	public function addItem($data){
		$data['ratio'] *= 0.01;
		if($this -> checkDuplicate($data)){
			$condition = array();
			$condition['classification_id'] = $data['classification_id'];
			$condition['area'] = $data['area'];
			$this -> where($condition) -> save($data);
		}else{
			$this -> add($data);
		}
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