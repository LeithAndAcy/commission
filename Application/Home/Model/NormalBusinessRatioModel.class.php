<?php
namespace Home\Model;
use Think\Model;
class NormalBusinessRatioModel extends Model {
	
	public function getAllNormalBusinessRatio(){
		
		$res = $this -> query("select id,salesman_id,inventory_id,ltrim(str(ratio * 100,12,2)) as ratio  from commission_normal_business_ratio");
		return $res;
	}
	
	public function addNormalBusinessRatio($data){
		$data['ratio'] *= 0.01;
		$condition = array();
		$condition['salesman_id'] =$data['salesman_id'];
		$condition['inventory_id'] = $data['inventory_id'];
		if($this -> where($condition) -> find()){
			$this -> where($condition) -> save($data);
		}else{
			$this -> add($data);
		}
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
	public function getNormalBusinessRatio($salesman_id,$inventory_id){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$condition['inventory_id'] = $inventory_id;
		$res = $this -> where($condition) -> getField('ratio');
		if($res == null){
			$condition['inventory_id'] = '其他';
			$res = $this -> where($condition) -> getField('ratio');
		}
		return $res;
	}
}
?>