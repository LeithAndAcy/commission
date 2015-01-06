<?php
namespace Home\Model;
use Think\Model;
class SpecialProfitRatioModel extends Model {
	
	public function getAllItems(){
		$res = $this->select();
		foreach ($res as $key => $value) {
			$res[$key]['ratio'] *= 100;
		}
		return $res;
	}
	
	public function addItem($data){
		$data['ratio'] *= 0.01;
		$condition['salesman_id'] = $data['salesman_id'];
		$temp = $this -> where($condtion)->select();
		foreach ($temp as $key => $value) {
			if(($data['low_limit'] >= $value['low_limit'] && $data['low_limit']<=$value['high_limit']) ||
			 ($data['high_limit'] <= $value['high_limit'] && $data['high_limit'] >= $value['low_limit'])){
				return FALSE;
			}
		}
		$this -> add($data);
	}
	public function editItem($id,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('ratio',$ratio*0.01);
	}
	public function deleteItemById($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}
}
?>