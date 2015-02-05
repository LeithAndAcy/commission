<?php
namespace Home\Model;
use Think\Model;
class NormalProfitRatioModel extends Model {
	
	public function getAllNormalProfitRatio(){
		$res = $this -> query("select id,salesman_id,ltrim(str(ratio * 100,12,2)) as ratio  from commission_normal_profit_ratio");
		return $res;
	}
	
	public function addNormalProfitRatio($data){
		$data['ratio'] *= 0.01;	
		$condition = array();
		$condition['salesman_id'] = $data['salesman_id'];
		if($this -> where($condition) -> find()){
			$this -> where($condition) -> save($data);
		}else{
			$this -> add($data);
		}
		
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