<?php
namespace Home\Model;
use Think\Model;
class TaxRatioModel extends Model {
	
	public function getAllItems(){
		$res = $this->select();
		foreach ($res as $key => $value) {
			$res[$key]['ratio'] *= 100;
			if($res[$key]['low_limit'] == ".00"){
				$res[$key]['low_limit'] = "0.00";
			}
		}
		return $res;
	}
	public function editItem($id,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('ratio',$ratio * 0.01);
	}
	public function addItem($data){
		$data['ratio'] *= 0.01;
		$this -> add($data);	
	}
	public function deleteItem($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition)-> delete();
	}
	public function getTaxRatio($left_money){
		$res = $this -> query("select ratio from commission_tax_ratio where (low_limit <= '$left_money') AND (high_limit > '$left_money')");
		return $res[0]['ratio'];
	}
}
?>