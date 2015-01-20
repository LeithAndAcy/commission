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
	
	// 取出salesman 对应的比例。
	public function getRatio($salesman_id_list){
		// $ids = implode(',', $salesman_id_list);
		// $sql = "select * from commission_normal_business_ratio where salesman_id in (".$ids.")";
		// $res = $this -> query($sql); 
		// print_r($sql);
		// print_r($res);exit;
	}
}
?>