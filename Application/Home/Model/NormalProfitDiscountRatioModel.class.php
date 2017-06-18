<?php
namespace Home\Model;
use Think\Model;
class NormalProfitDiscountRatioModel extends Model {
	
	public function getAllNormalProfitDiscountRatio(){
		$res = $this -> query("select id,salesman_id,date,ltrim(str(ratio * 100,12,2)) as ratio  from commission_normal_profit_discount_ratio");
		return $res;
	}
	
	public function addNormalProfitDiscountRatio($data){
		$data['ratio'] *= 0.01;	
		$condition = array();
		$condition['salesman_id'] = $data['salesman_id'];
		$condition['date'] = $data['date'];
		if($this -> where($condition) -> find()){
			$this -> where($condition) -> save($data);
		}else{
			$this -> add($data);
		}
		
	}
	public function edtiNormalProfitDiscountRatio($id,$date,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$data['ratio'] = $ratio*0.01;
		$data['date'] = $date;
		$this -> where($condition) -> save($data);
	}
	public function deleteNormalProfitDiscountRatioById($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}
}
?>