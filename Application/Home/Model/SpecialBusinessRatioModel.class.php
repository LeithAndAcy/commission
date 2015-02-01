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
		$condition['salesman_id'] = $data['salesman_id'];
		$condition['classification_id'] = $data['classification_id'];
		$temp = $this -> where($condition)->select();
		foreach ($temp as $key => $value) {
			if(($data['low_limit'] >= $value['low_limit'] && $data['low_limit']<=$value['high_limit']) ||
			 ($data['high_limit'] <= $value['high_limit'] && $data['high_limit'] >= $value['low_limit'])){
				return false;
			}
		}
		$this -> add($data);
		return true;
	}
	public function edtiSpecialBusinessRatio($id,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('ratio',$ratio*0.01);
	}
	public function deleteSpecialBusinessRatioById($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}
	public function getSpecialBusinessRatio($salesman_id,$classification_id,$funds){
		$condition = array();
		$res = $this -> query("select ratio from commission_special_business_ratio where (classification_id = '$classification_id') AND(salesman_id = '$salesman_id') AND(low_limit <= '$funds') AND(high_limit > '$funds')");
		$ratio = $res[0]['ratio'];
		return $ratio;
	}
}
?>