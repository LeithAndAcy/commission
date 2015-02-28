<?php
namespace Home\Model;
use Think\Model;
class WageDeductionModel extends Model {
	
	public function getItemsByMonth($month){
		$condition = array();
		$condition['date'] = $month;
		$res = $this->where($condition) ->select();
		
		return $res;
	}
	
	public function addItem($data){
		$condition = array();
		$condition['salesman_id'] = $data['salesman_id'];
		$condition['date'] = $data['date'];
		if($this -> where($condition) -> find()){
			$this -> where($condition) -> save($data);
		}else{
			$this -> add($data);
		}
		
	}
	public function getHumanWage($salesman_id,$month){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$condition['date'] = $month;
		$res = $this -> where($condition)-> getField('human_wage');
		return $res;
	}
	public function getTotalDeduction($salesman_id,$month){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$condition['date'] = $month;
		$res = $this -> where($condition)-> getField('total');
		return $res;
	}
}
?>