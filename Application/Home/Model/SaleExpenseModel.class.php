<?php
namespace Home\Model;
use Think\Model;
class SaleExpenseModel extends Model {
	
	public function getAllItems(){
		$res = $this->select();
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
	public function editItem($id,$sale_expense){
		$condition = array();
		$condition['id'] = $id;
		$this -> where($condition) -> setField('sale_expense',$sale_expense);
	}
	public function addItem($data){
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
			$arr[$value['salesman_id']][$value['inventory_id']] = $value['sale_expense'];
		}
		return $arr;
	}
}
?>