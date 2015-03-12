<?php
namespace Home\Model;
use Think\Model;
class SalaryModel extends Model {
	
	public function addItem($data){
		$this -> add($data);	
	}
	public function getSalary($month){
		$condition = array();
		$condition['date'] = $month;
		$res = $this -> where($condition) -> select();
		return $res;
	}
	public function getSalaryBySalesmanIdAndMonth($salesman_id,$month){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$condition['date'] = $month;
		$res = $this -> where($condition) -> find();
		if($res['status'] == "上海"){
			$temp = $res['shanghai_salary'];
		}elseif($res['status'] == "昆山"){
			$temp = $res['kunshan_salary'];
		}else{
			$temp = 0;
		}
		return $temp;
	}
	public function getShanghaiSalary($month){
		$condition = array();
		$condition['date'] = $month;
		$condition['status'] = "上海";
		$res = $this -> where($condition) -> select();
		return $res;
	}
	
	public function getAllShanghaiSalary($Page){
		$condition = array();
		$condition['status'] = "上海";
		$res = $this -> where($condition)-> order('date desc')->limit($Page->firstRow.','.$Page->listRows) -> select();
		return $res;
	}
	public function countAllShanghaiSalary(){
		$condition = array();
		$condition['status'] = "上海";
		$res = $this -> where($condition) -> count();
		return $res;
	}
	public function getAllKunshanSalary($Page){
		$condition = array();
		$condition['status'] = "昆山";
		$res = $this -> where($condition) -> order('date desc')->limit($Page->firstRow.','.$Page->listRows) -> select();
		return $res;
	}
	public function countAllKunshanSalary(){
		$condition = array();
		$condition['status'] = "昆山";
		$res = $this -> where($condition) -> count();
		return $res;
	}
	public function getKunshanSalary($month){
		$condition = array();
		$condition['date'] = $month;
		$condition['status'] = "昆山";
		$res = $this -> where($condition) -> select();
		return $res;
	}
	public function getAllSalary($Page){
		$res = $this -> order('date desc')->limit($Page->firstRow.','.$Page->listRows) -> select();
		return $res;
	}
}
?>