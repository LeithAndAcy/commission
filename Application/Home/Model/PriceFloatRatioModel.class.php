<?php
namespace Home\Model;
use Think\Model;
class PriceFloatRatioModel extends Model {
	
	public function getAllPriceFloatRatio(){
		$res = $this->select();
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
	public function getRatio($classification_id,$cost_price,$length){
		$ratio = $this -> query("select ratio from commission_price_float_ratio where (classification_id = '$classification_id') AND (low_price <= '$cost_price') AND (high_price >= '$cost_price') AND (low_length <= '$length') AND (high_length>'$length') ");
		print_r($ratio);exit;
		return $ratio;
	}
}
?>