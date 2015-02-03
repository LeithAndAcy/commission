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
	public function getShanghaiSalary($month){
		$condition = array();
		$condition['date'] = $month;
		$condition['status'] = "上海";
		$res = $this -> where($condition) -> select();
		return $res;
	}
	public function getAllShanghaiSalary(){
		$condition = array();
		$condition['status'] = "上海";
		$res = $this -> where($condition) -> select();
		return $res;
	}
	public function getAllKunshanSalary(){
		$condition = array();
		$condition['status'] = "昆山";
		$res = $this -> where($condition) -> select();
		return $res;
	}
	public function getKunshanSalary($month){
		$condition = array();
		$condition['date'] = $month;
		$condition['status'] = "昆山";
		$res = $this -> where($condition) -> select();
		return $res;
	}
	public function getAllSalary(){
		$res = $this -> select();
		return $res;
	}
}
?>