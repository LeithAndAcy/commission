<?php
namespace Home\Model;
use Think\Model;
class WageDeductionModel extends Model {
	
	public function getItemsByMonth($month){
		$condition = array();
		$condition['date'] = $month;
		$res = $this->select();
		return $res;
	}
	
	public function getHandledHumanWageByMonth($month){
		$condition = array();
		$condition['date'] = $month;
		$arr_wage = array();
		$res = $this->select();
		foreach ($res as $key => $value) {
			$arr_wage[$value['salesman_id']] = $value['total'];
		}
		return $arr_wage;
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
		//工资减去扣款后的合计
		$res = $this -> where($condition)-> getField('total');
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