<?php
namespace Home\Model;
use Think\Model;
class TaxRatioModel extends Model {
	
	public function getAllItems(){
		$res = $this -> query("select id,ltrim(str(low_limit,11,2)) as low_limit,ltrim(str(high_limit,11,2)) as high_limit,ltrim(str(ratio*100,5,2)) as ratio from commission_tax_ratio where ratio != -100");
		return $res;
	}
	// ratio = -100 存储税开始征收的钱
	public function getTaxBeginningPoint(){
		$condition = array();
		$condition['ratio'] = -100;
		$res = $this -> where($condition) -> getField("low_limit");
		return $res;
	}
	public function editTaxBeginningPoint($value){
		$condition = array();
		$condition['ratio'] = -100;
		$data['low_limit'] = $value;
		$data['high_limit'] = $value;
		$this -> where($condition) -> save($data);
	}
	public function editItem($id,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('ratio',$ratio * 0.01);
	}
	public function addItem($data){
		$data['ratio'] *= 0.01;
		$condition = array();
		$condition['low_limit'] = $data['low_limit'];
		$condition['high_limit'] = $data['high_limit'];
		if($this -> where($condition)->find()){
			$this -> where($condition) -> save($data);
			return true;
		}else{
			$temp_low_limit = $data['low_limit'];
			$temp_high_limit = $data['high_limit'];
			$temp = $this -> query("select id,ratio from commission_tax_ratio where(('$temp_low_limit' <= low_limit AND  '$temp_high_limit' > low_limit ) OR ('$temp_high_limit' < high_limit AND '$temp_high_limit' >= low_limit))");
			if($temp){
				return false;
			}else{
				$this -> add($data);
				return true;
			}
		}
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