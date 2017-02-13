<?php
namespace Home\Model;
use Think\Model;
class SalesmanFundsModel extends Model {
	public function addItems($array){
		$this ->query("truncate table commission_salesman_funds");
		foreach ($array as $key => $value) {
			$data["salesman_id"] = $value['salesman_id'];
			$data["salesman_name"] = $value['salesman_name'];
			$data["funds"] = $value['funds'];
			$data["month"] = $value['month'];
			$this -> add($data);
		}
	}
	public function getAllHandledSalesmanFunds(){
		$arr = array();
		$res = $this -> select();
		foreach ($res as $key => $value) {
			$arr[$value['salesman_id']] = $value['funds'];
		}
		return $arr;
	}
}
?>