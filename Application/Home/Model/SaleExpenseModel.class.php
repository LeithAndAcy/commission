<?php
namespace Home\Model;
use Think\Model;
class SaleExpenseModel extends Model {
	
	public function getAllItems(){
		$res = $this->select();
		foreach ($res as $key => $value) {
			$res[$key]['ratio'] *= 100;
			$res[$key]['sale_expense'] *= 100;
		}
		return $res;
	}
	public function getSaleExpense($salesman_id,$inventory_id){
		$condition = array();
		$condition['salesman_id'] = $salesman_id;
		$condition['inventory_id'] = $inventory_id;
		$res = $this -> where($condition) -> getField('sale_expense');
		if($res == null){
			return 0;
		}else{
			return $res;	
		}
	}
	public function editItem($id,$sale_expense,$ratio){
		$condition = array();
		$condition['id'] = $id;
		$data['sale_expense'] = $sale_expense* 0.01;
		$data['ratio'] = $ratio * 0.01;
		$this -> where($condition) -> save($data);
	}
	public function addItem($data){
		$data['ratio'] *= 0.01;
		$data['sale_expense'] *= 0.01;
		$this -> add($data);
	}
	public function deleteItem($id){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> delete();
	}
	public function getAllHandledSaleExpense(){
		$arr = array();	
		$res = $this->select();
		foreach ($res as $key => $value) {
			$arr[$value['salesman_id']][$value['contact_id']]['sale_expense'] = $value['sale_expense'];
			$arr[$value['salesman_id']][$value['contact_id']]['sale_expense_ratio'] = $value['ratio'];
		}
		return $arr;
	}
}
?>