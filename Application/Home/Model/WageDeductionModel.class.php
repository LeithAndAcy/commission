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
		$this -> add($data);	
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