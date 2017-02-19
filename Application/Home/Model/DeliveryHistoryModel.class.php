<?php
namespace Home\Model;
use Think\Model;
class DeliveryHistoryModel extends Model {
	
	
	public function addItems($array,$date){
		$month = date('Y-m',strtotime($date));
		foreach ($array as $key => $value) {
			$data = array();
			$data['salesman_id'] = $value['salesman_id'];
			$data['salesman_name'] = $value['salesman_name'];
			$data['customer_id'] = $value['customer_id'];
			$data['customer_name'] = $value['customer_name'];
			$data['contact_id'] = $value['contact_id'];
			$data['cSOCode'] = $value['cSOCode'];
			$data['inventory_id'] = $value['inventory_id'];
			$data['delivery_quantity'] = $value['delivery_quantity'];
			$data['inventory_name'] = $value['inventory_name'];
			$data['specification'] = $value['specification'];
			if($value['colour'] == null){
				$data['colour'] = '无';
			}else{
				$data['colour'] = $value['colour'];
			}
			$data['colour'] = $value['colour'];
			$data['date'] = $month;
			$this -> add($data);
		}	
	}
	
	public function searchByCondition($condition){
		$res = $this -> where($condition) -> select();
		return $res;
	}

}
?>